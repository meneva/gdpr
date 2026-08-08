<?php

namespace App\Policies;

use App\Models\ProcessingActivity;
use App\Models\User;

class ProcessingActivityPolicy
{
    protected function canManage(User $user, int $companyId): bool
    {
        return $user->hasCompanyRole($companyId, ['owner', 'admin', 'editor']);
    }

    public function viewAny(User $user): bool
    {
        return $user->current_company_id !== null;
    }

    public function view(User $user, ProcessingActivity $activity): bool
    {
        return $user->hasCompanyRole($activity->company_id, ['owner', 'admin', 'editor', 'viewer']);
    }

    public function create(User $user): bool
    {
        return $user->current_company_id
            && $this->canManage($user, $user->current_company_id);
    }

    public function update(User $user, ProcessingActivity $activity): bool
    {
        return $this->canManage($user, $activity->company_id);
    }

    public function delete(User $user, ProcessingActivity $activity): bool
    {
        return $user->hasCompanyRole($activity->company_id, ['owner', 'admin']);
    }
}
