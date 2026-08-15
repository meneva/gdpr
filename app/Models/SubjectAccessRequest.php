<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\GeneratesRefNumbers;
use App\Support\Deadlines;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectAccessRequest extends Model
{
    use BelongsToCompany, GeneratesRefNumbers;

    protected $fillable = [
        'company_id', 'requester_name', 'requester_type', 'received_at',
        'deadline_at', 'status', 'assigned_to', 'notes', 'closed_at', 'reminder_sent_at',
    ];

    protected $casts = [
        'received_at' => 'date',
        'deadline_at' => 'date',
        'closed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $sar) {
            $sar->received_at ??= now()->toDateString();
            $sar->deadline_at ??= Deadlines::sarDeadline($sar->received_at);
            $sar->status ??= 'received';
        });
    }

    protected function refPrefix(): string
    {
        return 'SAR';
    }

    protected function refSequenceColumn(): string
    {
        return 'sar_sequence';
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function daysRemaining(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->deadline_at, false);
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'completed' && $this->daysRemaining() < 0;
    }
}
