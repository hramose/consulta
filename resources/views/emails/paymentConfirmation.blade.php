@component('mail::message')
# Confirmacion de pago 

-Numero de operación: {{ $purchaseOperationNumber }} 

@component('mail::table')
| Cant. | Descripción         | Subtotal |
| -----:|:-------------------:| --------:|
@foreach($incomes as $income)
| 1     | {{ $income->title }}| {{ money($income->amount, '$')}}|
@endforeach
|       |              Total: | {{ money($total, '$')}}|
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent
