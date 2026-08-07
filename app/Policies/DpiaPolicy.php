<?php

namespace App\Policies;

use App\Models\Dpia;
use App\Models\User;

class DpiaPolicy
{
    protected function canManage(User $user, int $companyId): bool
    {
        return $user->hasCompanyRole($companyId, ['owner', 'admin', 'editor']);
    }

    public function viewAny(User $user): bool
    {
        return $user->current_company_id !== null;
    }

    public function view(User $user, Dpia $dpia): bool
    {
        return $user->hasCompanyRole($dpia->company_id, ['owner', 'admin', 'editor', 'viewer']);
    }

    public function create(User $user): bool
    {
        return $user->current_company_id
            && $this->canManage($user, $user->current_company_id);
    }

    public function update(User $user, Dpia $dpia): bool
    {
        return $this->canManage($user, $dpia->company_id);
    }

    /**
     * Approving/rejecting is a step above ordinary editing — restricted
     * to owner/admin, the same tier that can delete records.
     */
    public function approve(User $user, Dpia $dpia): bool
    {
        return $user->hasCompanyRole($dpia->company_id, ['owner', 'admin']);
    }

    public function delete(User $user, Dpia $dpia): bool
    {
        return $user->hasCompanyRole($dpia->company_id, ['owner', 'admin']);
    }
}
