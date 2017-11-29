@component('mail::message')

# Cambio de contraseña

Utiliza este codigo para poder cambiar tu contraseña

- Código: {{ $code }} 



Gracias,<br>
{{ config('app.name') }}
@endcomponent
