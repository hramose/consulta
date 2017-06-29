@component('mail::message')
# Nueva Cita 

Cita con el Dr. {{ $appointment->user->name }}

- Fecha: {{ $appointment->date->toDateString() }} De: {{   Carbon\Carbon::parse($appointment->start)->ToTimeString() }} a: {{   Carbon\Carbon::parse($appointment->end)->ToTimeString() }}
- Paciente: {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}


Gracias,<br>
{{ config('app.name') }}
@endcomponent
