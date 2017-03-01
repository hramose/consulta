@component('mail::message')
# Nueva Cita 

Cita con el Dr. {{ $appointment->user->name }}

- Fecha: 2017-03-01 De: 16:00 a: 16:30
- Paciente: {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}


Thanks,<br>
{{ config('app.name') }}
@endcomponent
