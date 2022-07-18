@component('mail::message')
    Olá {{ $user->name }}!

    Sua conta foi criada com sucesso!

    Saudações,
    {{ config('app.name') }}
@endcomponent
