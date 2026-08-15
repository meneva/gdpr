<?php

namespace App\Policies;

use App\Models\TrainingCourse;
use App\Models\User;

class TrainingCoursePolicy
{
    protected function canManage(User $user, int $companyId): bool
    {
        return $user->hasCompanyRole($companyId, ['owner', 'admin', 'editor']);
    }

    public function viewAny(User $user): bool
    {
        return $user->current_company_id !== null;
    }

    public function view(User $user, TrainingCourse $course): bool
    {
        return $user->hasCompanyRole($course->company_id, ['owner', 'admin', 'editor', 'viewer']);
    }

    public function create(User $user): bool
    {
        return $user->current_company_id
            && $this->canManage($user, $user->current_company_id);
    }

    public function update(User $user, TrainingCourse $course): bool
    {
        return $this->canManage($user, $course->company_id);
    }

    public function delete(User $user, TrainingCourse $course): bool
    {
        return $user->hasCompanyRole($course->company_id, ['owner', 'admin']);
    }
}
