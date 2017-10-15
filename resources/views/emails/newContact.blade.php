@component('mail::message')
# Tienes una consulta sobre {{ $dataMessage['modal-contact-subject'] }} por {{ $dataMessage['user']->name }} ({{ $dataMessage['user']->email }})

{{ $dataMessage['modal-contact-msg'] }}


Gracias,<br>
{{ config('app.name') }}
@endcomponent
