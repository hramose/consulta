@component('mail::message')
# Confirmacion de pago 

-Numero de operación: {{ $purchaseOperationNumber }} 

@component('mail::table')
| Cant. | Descripción         | Subtotal                           |
| -----:|:-------------------:| ----------------------------------:|
| 1     | {{ $plan->title }}  | {{ money($plan->cost, '$')}}       |
|       |     <b>Total:</b>   | <b>{{ money($plan->cost, '$')}}</b>|
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
