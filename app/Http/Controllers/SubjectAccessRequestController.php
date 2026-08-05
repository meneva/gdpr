<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectAccessRequestRequest;
use App\Http\Requests\UpdateSubjectAccessRequestRequest;
use App\Models\SubjectAccessRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubjectAccessRequestController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', SubjectAccessRequest::class);

        // The BelongsToCompany global scope already restricts this to the
        // current company — no ->where('company_id', ...) needed here.
        $sars = SubjectAccessRequest::query()
            ->latest('received_at')
            ->paginate(20);

        return view('sars.index', compact('sars'));
    }

    public function create(): View
    {
        $this->authorize('create', SubjectAccessRequest::class);

        return view('sars.create', [
            'assignees' => $this->companyMembers(),
        ]);
    }

    public function store(StoreSubjectAccessRequestRequest $request): RedirectResponse
    {
        $this->authorize('create', SubjectAccessRequest::class);

        // Transaction matters here: GeneratesRefNumbers takes a row lock on
        // the company while incrementing sar_sequence, and that lock is
        // only meaningful for the life of this transaction.
        $sar = DB::transaction(fn () => SubjectAccessRequest::create($request->validated()));

        return redirect()
            ->route('sars.show', $sar)
            ->with('status', "Logged {$sar->ref_no}.");
    }

    public function show(SubjectAccessRequest $sar): View
    {
        $this->authorize('view', $sar);

        return view('sars.show', compact('sar'));
    }

    public function edit(SubjectAccessRequest $sar): View
    {
        $this->authorize('update', $sar);

        return view('sars.edit', [
            'sar' => $sar,
            'assignees' => $this->companyMembers(),
        ]);
    }

    public function update(UpdateSubjectAccessRequestRequest $request, SubjectAccessRequest $sar): RedirectResponse
    {
        $this->authorize('update', $sar);

        $sar->update($request->validated());

        return redirect()->route('sars.show', $sar)->with('status', 'Updated.');
    }

    public function destroy(SubjectAccessRequest $sar): RedirectResponse
    {
        $this->authorize('delete', $sar);

        $sar->delete();

        return redirect()->route('sars.index')->with('status', 'Deleted.');
    }

    /**
     * Members of the current company, for the "assign to" dropdown.
     * Company::users() is unscoped by BelongsToCompany (Company itself
     * doesn't use that trait), so this stays a plain query.
     */
    protected function companyMembers()
    {
        return \App\Models\Company::query()
            ->whereKey(session('current_company_id'))
            ->first()
            ?->users
            ?? collect();
    }
}
