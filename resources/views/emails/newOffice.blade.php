@component('mail::message')
# Nuevo Consultorio o clínica

Se ha registrado un nuevo consultorio o clinica a un médico en el sistema. Verfícalo
# Medico:
- Nombre: {{ $medic->name }} 
- Correo: {{ $medic->email }}
- Telefono: {{ $medic->phone }}

# Clinica:
- Nombre: {{ $office->name }} 
- Dirección: {{ $office->address }}
- Telefono: {{ $office->phone }}

@component('mail::button', ['url' => env('APP_URL').'/clinics/'. $office->id .'/profile'])
Link a compartir con el administrador de la clinica
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
