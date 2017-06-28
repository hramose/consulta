@component('mail::message')
# No olvides presentarte a tu cita 

Cita con el Dr. {{ $appointment->user->name }}

- Fecha: 2017-03-01 De: 16:00 a: 16:30
- Paciente: {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}

Si por alguna razón no puedes ir, puedes cancerlar la cita dandole click al siguiente boton:

@component('mail::button', ['url' => env('APP_URL').'/appointments','color' => 'red'])
Cancelar Cita
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
