@component('mail::message')
# Confirmacion de pago 

-Numero de operación: {{ $purchaseOperationNumber }} 

@component('mail::table')
| Cant. | Descripción               | Subtotal                        |
| -----:|:-------------------------:| -------------------------------:|
@foreach($incomes as $income)
| 1     | {{ $income->description }}| {{ money($income->amount, '$')}}|
@endforeach
|       |             <b>Total:</b> | <b>{{ money($total, '$')}}</b>  |
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
