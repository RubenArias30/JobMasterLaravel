<x-mail::message>
# Solicitud para Cambiar Contraseña

Para cambiar tu contraseña, haz clic en el botón de abajo:

{{-- <x-mail::button :url="'http://localhost:4200/response-password-reset?token='.$token" color="primary"> --}}
<x-mail::button :url="'https://jobmaster.es/response-password-reset?token='.$token" color="primary">
Cambiar Contraseña
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
