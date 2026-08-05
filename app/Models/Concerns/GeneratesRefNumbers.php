<?php

namespace App\Models\Concerns;

use App\Models\Company;

/**
 * Apply alongside BelongsToCompany on any model that needs a human-readable,
 * per-company reference number (SAR-2026-014, BRC-2026-008, ...).
 *
 * Uses a row lock on the owning company while incrementing its sequence
 * counter, so two requests created for the same company at the same moment
 * can't collide on the same number. Wrap the calling create() in
 * DB::transaction() (see SubjectAccessRequestController) so the lock is
 * actually held for the duration of the write.
 */
trait GeneratesRefNumbers
{
    protected static function bootGeneratesRefNumbers(): void
    {
        static::creating(function ($model) {
            if (empty($model->ref_no)) {
                $model->ref_no = $model->generateRefNo();
            }
        });
    }

    protected function generateRefNo(): string
    {
        $column = $this->refSequenceColumn();

        $company = Company::query()
            ->whereKey($this->company_id)
            ->lockForUpdate()
            ->firstOrFail();

        $company->increment($column);

        return sprintf('%s-%s-%03d', $this->refPrefix(), now()->format('Y'), $company->{$column});
    }

    abstract protected function refPrefix(): string;

    abstract protected function refSequenceColumn(): string;
}
