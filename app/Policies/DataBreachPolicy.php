<?php

namespace App\Policies;

use App\Models\DataBreach;
use App\Models\User;

class DataBreachPolicy
{
    protected function canManage(User $user, int $companyId): bool
    {
        return $user->hasCompanyRole($companyId, ['owner', 'admin', 'editor']);
    }

    public function viewAny(User $user): bool
    {
        return $user->current_company_id !== null;
    }

    public function view(User $user, DataBreach $breach): bool
    {
        return $user->hasCompanyRole($breach->company_id, ['owner', 'admin', 'editor', 'viewer']);
    }

    public function create(User $user): bool
    {
        return $user->current_company_id
            && $this->canManage($user, $user->current_company_id);
    }

    public function update(User $user, DataBreach $breach): bool
    {
        return $this->canManage($user, $breach->company_id);
    }

    public function delete(User $user, DataBreach $breach): bool
    {
        return $user->hasCompanyRole($breach->company_id, ['owner', 'admin']);
    }
}
