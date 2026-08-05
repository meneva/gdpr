<?php

namespace App\Http\Controllers;

use App\Mail\CompanyInvitationMail;
use App\Models\Company;
use App\Models\CompanyInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyInvitationController extends Controller
{
    public function index(Request $request): View
    {
        $company = $this->currentCompany();
        $this->authorize('manageMembers', $company);

        return view('companies.members', [
            'company' => $company,
            'invitations' => $company->invitations()->latest()->get(),
            'members' => $company->users()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $company = $this->currentCompany();
        $this->authorize('manageMembers', $company);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'in:admin,editor,viewer'],
        ]);

        if ($company->users()->where('email', $data['email'])->exists()) {
            return back()->withErrors(['email' => 'That person is already a member of this company.']);
        }

        $invitation = $company->invitations()->create([
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => Str::random(48),
            'invited_by' => $request->user()->id,
            'expires_at' => now()->addDays(7),
        ]);

        $acceptUrl = route('invitations.accept', $invitation->token);

        try {
            Mail::to($invitation->email)->send(new CompanyInvitationMail($invitation, $acceptUrl));
            $status = "Invitation sent to {$invitation->email}.";
        } catch (\Throwable $e) {
            // Mail may not be configured yet on a fresh server. Don't let
            // that block the invite from being created — surface the link
            // directly so it can be shared by hand in the meantime.
            $status = "Invitation created, but mail isn't configured yet. Share this link directly: {$acceptUrl}";
        }

        return back()->with('status', $status);
    }

    public function destroy(Request $request, CompanyInvitation $invitation): RedirectResponse
    {
        $this->authorize('manageMembers', $invitation->company);

        $invitation->delete();

        return back()->with('status', 'Invitation revoked.');
    }

    /**
     * Public accept link. Requires login (see routes/company.php) but
     * deliberately sits OUTSIDE ensure.company.selected — the whole point
     * is letting someone without a company yet (or with other companies
     * already) join a new one.
     */
    public function accept(Request $request, string $token): RedirectResponse
    {
        $invitation = CompanyInvitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('dashboard')->withErrors([
                'invitation' => 'This invitation has expired. Ask an admin to send a new one.',
            ]);
        }

        $user = $request->user();

        abort_unless(
            strcasecmp($user->email, $invitation->email) === 0,
            403,
            "This invitation was sent to {$invitation->email}, not to your account ({$user->email})."
        );

        DB::transaction(function () use ($invitation, $user) {
            $invitation->company->users()->syncWithoutDetaching([
                $user->id => ['role' => $invitation->role, 'joined_at' => now()],
            ]);

            $invitation->delete();
        });

        $user->update(['current_company_id' => $invitation->company_id]);
        session(['current_company_id' => $invitation->company_id]);

        return redirect()->route('dashboard')->with('status', "You've joined {$invitation->company->name}.");
    }

    protected function currentCompany(): Company
    {
        return Company::query()->findOrFail(session('current_company_id'));
    }
}
