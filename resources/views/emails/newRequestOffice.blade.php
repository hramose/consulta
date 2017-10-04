@component('mail::message')
# Nueva solicitud de integración de clínica

Se ha solicitado la integracion de la siguiente clínica. Verfícalo

# Clinica:
- Nombre: {{ $requestOffice->name }} 
- Dirección: {{ $requestOffice->address }}
- Telefono: {{ $requestOffice->phone }}

# Usuario que la solicitó:
- Nombre: {{ $requestOffice->user->name }}
- Correo: {{ $requestOffice->user->email }} 


@component('mail::button', ['url' => env('APP_URL').'/admin/offices/requests'])
Ver las solicitudes de integración
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
