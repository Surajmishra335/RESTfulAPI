@component('mail::message')
# Hello {{$user->name}}

You have changed the email. so we need to verify this new address. please click on button:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent