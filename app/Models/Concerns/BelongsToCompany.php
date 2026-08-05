<?php

namespace App\Models\Concerns;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Apply to every tenant-owned model. It does two things:
 *
 * 1. Adds a global scope that filters every query to the company stored in
 *    session('current_company_id') — so a controller never has to remember
 *    to add ->where('company_id', ...) manually.
 * 2. Auto-fills company_id on create if it wasn't set explicitly.
 *
 * Because this relies on session(), it only applies in the web (session-
 * backed) context. API tokens/queued jobs must set company_id explicitly.
 */
trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if ($companyId = session('current_company_id')) {
                $builder->where($builder->getModel()->getTable().'.company_id', $companyId);
            }
        });

        static::creating(function ($model) {
            $model->company_id ??= session('current_company_id');
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
