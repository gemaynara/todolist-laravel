@component('mail::message')
    Olá {{ $user['name'] }}!

    Sua conta foi excluída  com sucesso!

    Saudações,
    {{ config('app.name') }}
@endcomponent
