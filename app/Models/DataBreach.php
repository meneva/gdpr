<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\GeneratesRefNumbers;
use App\Support\Deadlines;
use Illuminate\Database\Eloquent\Model;

class DataBreach extends Model
{
    use BelongsToCompany, GeneratesRefNumbers;

    protected $fillable = [
        'company_id', 'title', 'description', 'severity', 'discovered_at',
        'notify_deadline_at', 'status', 'ico_notified_at', 'resolved_at', 'reminder_sent_at',
    ];

    protected $casts = [
        'discovered_at' => 'datetime',
        'notify_deadline_at' => 'datetime',
        'ico_notified_at' => 'datetime',
        'resolved_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $breach) {
            $breach->discovered_at ??= now();
            $breach->notify_deadline_at ??= Deadlines::breachNotifyDeadline($breach->discovered_at);
            $breach->status ??= 'assessing';
        });
    }

    protected function refPrefix(): string
    {
        return 'BRC';
    }

    protected function refSequenceColumn(): string
    {
        return 'breach_sequence';
    }

    /**
     * Whole hours left until the 72-hour ICO notification deadline.
     * Negative once the window has passed.
     */
    public function hoursRemaining(): int
    {
        return (int) floor(now()->floatDiffInHours($this->notify_deadline_at, false));
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'resolved' && $this->hoursRemaining() < 0;
    }
}
