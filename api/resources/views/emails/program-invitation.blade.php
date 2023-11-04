<x-mail::message>
# Hello Champion!

You have been invited to join the {{ $invitation->program->name }} program. Please click the button below to accept
the invitation.

<x-mail::button :url="$url">Accept Invitation</x-mail::button>

This invitation will expire in {{ $invitation->expires_at->diffForHumans() }} on
{{ $invitation->expires_at->toDayDateTimeString() }}. If you do not accept the invitation
before then, you will have to request a new invitation from the program creator.

If you have any questions, please contact the program creator, {{ $invitation->program->creator->name }}at
{{ $invitation->program->creator->email }}.
</x-mail::message>
