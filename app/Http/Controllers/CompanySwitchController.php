<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanySwitchController extends Controller
{
    public function update(Request $request, int $company): RedirectResponse
    {
        $user = $request->user();

        abort_unless(
            $user->companies()->whereKey($company)->exists(),
            403,
            'You are not a member of that company.'
        );

        session(['current_company_id' => $company]);

        return back()->with('status', 'Switched company.');
    }
}
