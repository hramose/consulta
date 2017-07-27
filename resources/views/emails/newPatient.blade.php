@component('mail::message')
# Bienvenido

Se ha registrado un nuevo perfil de paciente con los siguientes datos:

- Nombre: {{ $user->name }} 
- Correo: {{ $user->email }}

La contraseña es la generica. Si no la recuerdas o quieres cambiarla, recuerda que puedes dar click en el link <a href="{{ env('APP_URL').'/password/reset' }}"><strong>Olvidaste tu contraseña?</strong></a> para cambiar tu contraseña mas segura.

@component('mail::button', ['url' => env('APP_URL')])
Iniciar Sesion
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
