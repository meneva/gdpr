<?php

namespace App\Notifications;

use App\Models\DataBreach;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BreachDeadlineApproaching extends Notification
{
    public function __construct(public DataBreach $breach)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $breach = $this->breach;
        $hoursLeft = $breach->hoursRemaining();
        $urgency = $hoursLeft < 0
            ? 'is now '.abs($hoursLeft).' hour(s) past'
            : 'is '.$hoursLeft.' hour(s) from';

        return (new MailMessage)
            ->subject("Urgent: {$breach->ref_no} ICO notification window {$urgency} closing")
            ->greeting("Incident {$breach->ref_no}")
            ->line("\"{$breach->title}\" {$urgency} the 72-hour ICO notification deadline ({$breach->notify_deadline_at->format('d M Y H:i')}).")
            ->action('View incident', route('breaches.show', $breach))
            ->line('This is an automated reminder from your GDPR compliance register.');
    }
}
