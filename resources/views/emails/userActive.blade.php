@component('mail::message')
# Tu usuario ha sido activado

Tu usuario ha sido activado. ya puedes iniciar sesion y hacer uso del sistema 

- Nombre: {{ $user->name }} 
- Correo: {{ $user->email }}

@component('mail::button', ['url' => env('APP_URL')])
Ir a la plataforma
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
