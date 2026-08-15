<?php

namespace App\Console\Commands;

use App\Models\DataBreach;
use App\Notifications\BreachDeadlineApproaching;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class RemindBreachDeadlines extends Command
{
    protected $signature = 'breach:remind-deadlines';

    protected $description = 'Email a one-time reminder for open breaches within 24 hours of the 72-hour ICO notification deadline';

    public function handle(): int
    {
        $breaches = DataBreach::query()
            ->where('status', '!=', 'resolved')
            ->whereNull('reminder_sent_at')
            ->where('notify_deadline_at', '<=', now()->addHours(24))
            ->with('company')
            ->get();

        $sent = 0;

        foreach ($breaches as $breach) {
            // No assignee field on DataBreach (unlike SAR) — always falls
            // back to the company's owner/admins.
            $recipients = $breach->company->notifiableRecipients();

            if ($recipients->isEmpty()) {
                continue;
            }

            Notification::send($recipients, new BreachDeadlineApproaching($breach));
            $breach->update(['reminder_sent_at' => now()]);
            $sent++;
        }

        $this->info("Sent {$sent} breach deadline reminder(s).");

        return self::SUCCESS;
    }
}
