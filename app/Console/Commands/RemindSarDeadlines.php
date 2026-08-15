<?php

namespace App\Console\Commands;

use App\Models\SubjectAccessRequest;
use App\Notifications\SarDeadlineApproaching;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class RemindSarDeadlines extends Command
{
    protected $signature = 'sar:remind-deadlines';

    protected $description = 'Email a one-time reminder for open SARs within 7 days of their 30-day deadline';

    public function handle(): int
    {
        // No session in a console context, so BelongsToCompany's global
        // scope adds no company filter here — this intentionally sees
        // every company's records, which is exactly what a cross-tenant
        // scheduled job needs.
        $sars = SubjectAccessRequest::query()
            ->where('status', '!=', 'completed')
            ->whereNull('reminder_sent_at')
            ->whereDate('deadline_at', '<=', now()->addDays(7))
            ->with(['company', 'assignee'])
            ->get();

        $sent = 0;

        foreach ($sars as $sar) {
            $recipients = $sar->assignee
                ? collect([$sar->assignee])
                : $sar->company->notifiableRecipients();

            if ($recipients->isEmpty()) {
                continue;
            }

            Notification::send($recipients, new SarDeadlineApproaching($sar));
            $sar->update(['reminder_sent_at' => now()]);
            $sent++;
        }

        $this->info("Sent {$sent} SAR deadline reminder(s).");

        return self::SUCCESS;
    }
}
