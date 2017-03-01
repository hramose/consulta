@component('mail::message')
# Nuevo Médico

Se ha registrado un nuevo usuario como médico en el sistema. Verfícalo

- Nombre: {{ $user->name }} 
- Correo: {{ $user->email }}

@component('mail::button', ['url' => 'http://consulta.avotz.com/'])
Ir a usuarios
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
