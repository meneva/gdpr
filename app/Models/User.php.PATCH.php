<?php

/**
 * This is NOT a drop-in replacement for app/Models/User.php.
 * Jetstream's default User model already has a lot in it (HasApiTokens,
 * HasProfilePhoto, TwoFactorAuthenticatable, etc). Merge the pieces below
 * into your existing User model instead of overwriting the file.
 */

// 1. Add these imports near the top:
use App\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// 2. Add 'current_company_id' to $fillable if you keep a $fillable array
//    (Jetstream's default User uses $fillable for name/email/password — add it there).

// 3. Add these methods inside the User class body:

public function currentCompanyRelation(): BelongsTo
{
    return $this->belongsTo(Company::class, 'current_company_id');
}

public function companies(): BelongsToMany
{
    return $this->belongsToMany(Company::class, 'company_user')
        ->withPivot('role', 'joined_at')
        ->withTimestamps();
}

/**
 * The role this user holds in a given company, or null if they're not a member.
 */
public function roleInCompany(int $companyId): ?string
{
    return $this->companies()->whereKey($companyId)->first()?->pivot->role;
}

/**
 * True if this user holds one of the given roles in the given company.
 * Used by every module's Policy class — see SubjectAccessRequestPolicy.
 */
public function hasCompanyRole(int $companyId, array $roles): bool
{
    $role = $this->roleInCompany($companyId);

    return $role !== null && in_array($role, $roles, true);
}
