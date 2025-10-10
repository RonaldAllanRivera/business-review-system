@component('mail::message')
# Welcome, {{ $user->name }}!

Thanks for joining Business Review System. We're excited to have you.

@component('mail::button', ['url' => config('app.url')])
Go to App
@endcomponent

If you have any questions, just reply to this email — we're here to help.

Thanks,
{{ config('app.name') }} Team
@endcomponent
