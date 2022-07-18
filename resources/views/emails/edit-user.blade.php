@component('mail::message')
    Olá {{ $user['name'] }}!

    Sua conta foi alterada com sucesso!

    Saudações,
    {{ config('app.name') }}
@endcomponent
