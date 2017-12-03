@component('mail::message')
# Integración de clínica realizada

Ya puedes agregar la clínica {{ $requestOffice->name }} a tu perfil para programar y recibir citas

Gracias,<br>
{{ config('app.name') }}
@endcomponent
