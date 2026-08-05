<?php

namespace App\Policies;

use App\Models\SubjectAccessRequest;
use App\Models\User;

class SubjectAccessRequestPolicy
{
    protected function canManage(User $user, int $companyId): bool
    {
        return $user->hasCompanyRole($companyId, ['owner', 'admin', 'editor']);
    }

    public function viewAny(User $user): bool
    {
        return $user->current_company_id !== null;
    }

    public function view(User $user, SubjectAccessRequest $sar): bool
    {
        return $user->hasCompanyRole($sar->company_id, ['owner', 'admin', 'editor', 'viewer']);
    }

    public function create(User $user): bool
    {
        return $user->current_company_id
            && $this->canManage($user, $user->current_company_id);
    }

    public function update(User $user, SubjectAccessRequest $sar): bool
    {
        return $this->canManage($user, $sar->company_id);
    }

    public function delete(User $user, SubjectAccessRequest $sar): bool
    {
        return $user->hasCompanyRole($sar->company_id, ['owner', 'admin']);
    }
}
