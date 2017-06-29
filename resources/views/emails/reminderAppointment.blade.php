@component('mail::message')
# No olvides presentarte a tu cita 

Cita con el Dr. {{ $appointment->user->name }}

- Fecha: {{ $appointment->date->toDateString() }} De: {{   Carbon\Carbon::parse($appointment->start)->ToTimeString() }} a: {{   Carbon\Carbon::parse($appointment->end)->ToTimeString() }}
- Paciente: {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}

Si por alguna razón no puedes ir, puedes cancerlar la cita dandole click al siguiente boton:

@component('mail::button', ['url' => env('APP_URL').'/appointments','color' => 'red'])
Cancelar Cita
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
