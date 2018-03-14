@component('mail::message')
# Nueva Anuncio test 

<img src="{{ public_path('img/logo.png') }}" alt="">
<img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path('img/excel.jpg')))}}" alt="">
{{ public_path('img/logo.png') }}
Gracias,<br>
{{ config('app.name') }}
@endcomponent
