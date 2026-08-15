<?php

namespace App\Notifications;

use App\Models\SubjectAccessRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SarDeadlineApproaching extends Notification
{
    public function __construct(public SubjectAccessRequest $sar)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $sar = $this->sar;
        $daysLeft = $sar->daysRemaining();
        $urgency = $daysLeft < 0
            ? 'is now '.abs($daysLeft).' day(s) overdue'
            : 'is due in '.$daysLeft.' day(s)';

        return (new MailMessage)
            ->subject("Reminder: {$sar->ref_no} {$urgency}")
            ->greeting("Subject access request {$sar->ref_no}")
            ->line("The request from {$sar->requester_name} {$urgency} — deadline {$sar->deadline_at->format('d M Y')}.")
            ->action('View request', route('sars.show', $sar))
            ->line('This is an automated reminder from your GDPR compliance register.');
    }
}
