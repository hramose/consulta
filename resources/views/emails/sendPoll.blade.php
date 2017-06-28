@component('mail::message')
# Encuesta de satisfacción del servicio brindado 

Ayudanos a mejorar. Califica y dejanos tus comentarios en el siguiente link:

@component('mail::button', ['url' => env('APP_URL').'/medics/'.$medic_id.'/polls'])
Contestar Encuesta
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
