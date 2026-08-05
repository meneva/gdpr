@component('mail::message')
# You're invited

{{ $invitation->inviter->name }} has invited you to join **{{ $invitation->company->name }}** as **{{ ucfirst($invitation->role) }}** on the GDPR compliance register.

@component('mail::button', ['url' => $acceptUrl])
Accept invitation
@endcomponent

This invitation expires on {{ $invitation->expires_at->format('d M Y') }}.

If the button above doesn't work, copy and paste this link into your browser:
{{ $acceptUrl }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
