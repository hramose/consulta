@component('mail::message')
# Confirmacion de pago 

-Numero de operación: {{ $purchaseOperationNumber }} 

@component('mail::table')
| Cant. | Descripción         | Subtotal |
| -----:|:-------------------:| --------:|
@foreach($incomes as $income)
| 1     | {{ $plan->title }}| {{ money($plan->cost, '$')}}|
@endforeach
|       |     <b>Total:</b> | <b>{{ money($plan->cost, '$')}}</b>|
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
