<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['name', 'slug', 'industry', 'timezone', 'owner_id', 'subscription_status'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(CompanyInvitation::class);
    }

    public function subjectAccessRequests(): HasMany
    {
        return $this->hasMany(SubjectAccessRequest::class);
    }

    public function dataBreaches(): HasMany
    {
        return $this->hasMany(DataBreach::class);
    }

    public function dpias(): HasMany
    {
        return $this->hasMany(Dpia::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function processingActivities(): HasMany
    {
        return $this->hasMany(ProcessingActivity::class);
    }

    // As you build each new module (DataBreach, Dpia, Supplier, ...),
    // add a matching hasMany() here — it keeps "everything this company
    // owns" discoverable from one place.
}
