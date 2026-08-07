<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\GeneratesRefNumbers;
use Illuminate\Database\Eloquent\Model;

class Dpia extends Model
{
    use BelongsToCompany, GeneratesRefNumbers;

    protected $fillable = [
        'company_id', 'project_name', 'owner_name', 'description',
        'risk_level', 'status', 'due_at', 'approved_at',
    ];

    protected $casts = [
        'due_at' => 'date',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $dpia) {
            $dpia->status ??= 'draft';
        });
    }

    protected function refPrefix(): string
    {
        return 'DPIA';
    }

    protected function refSequenceColumn(): string
    {
        return 'dpia_sequence';
    }

    protected function isClosed(): bool
    {
        return in_array($this->status, ['approved', 'rejected'], true);
    }

    public function daysRemaining(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->due_at, false);
    }

    public function isOverdue(): bool
    {
        return ! $this->isClosed() && $this->daysRemaining() < 0;
    }
}
