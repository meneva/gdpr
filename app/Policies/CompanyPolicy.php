<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function create(User $user): bool
    {
        // Any authenticated user can create a company — this is the
        // onboarding path for a brand-new account with no memberships yet.
        return true;
    }

    public function view(User $user, Company $company): bool
    {
        return $user->hasCompanyRole($company->id, ['owner', 'admin', 'editor', 'viewer']);
    }

    public function manageMembers(User $user, Company $company): bool
    {
        return $user->hasCompanyRole($company->id, ['owner', 'admin']);
    }

    public function update(User $user, Company $company): bool
    {
        return $user->hasCompanyRole($company->id, ['owner']);
    }
}
