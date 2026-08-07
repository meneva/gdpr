<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    protected function canManage(User $user, int $companyId): bool
    {
        return $user->hasCompanyRole($companyId, ['owner', 'admin', 'editor']);
    }

    public function viewAny(User $user): bool
    {
        return $user->current_company_id !== null;
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return $user->hasCompanyRole($supplier->company_id, ['owner', 'admin', 'editor', 'viewer']);
    }

    public function create(User $user): bool
    {
        return $user->current_company_id
            && $this->canManage($user, $user->current_company_id);
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $this->canManage($user, $supplier->company_id);
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->hasCompanyRole($supplier->company_id, ['owner', 'admin']);
    }
}
