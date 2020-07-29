@component('mail::message')
# Hello {{$user->name}}

Thankyou for creating a account. please verify your account by clicking this Button:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent