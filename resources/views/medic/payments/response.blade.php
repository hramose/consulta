@extends('layouts.app')

@section('content')

@include('layouts/partials/header-pages',['page'=>'Comprobante de pago'])

	<div class="payment">



            <h1 class="payment__title">Comprobante de pago</h1>

            <section class="panel payment__method__card">

                @if(isset($authorizationResult) && isset($purchaseOperationNumber))

                    @if($authorizationResult == 00 || $authorizationResult == "Success")
                        <div class="alert alert-info">Pago realizado con exito</div>
                    @endif
                    @if($authorizationResult == 01)
                            <div class="alert alert-danger">La operación ha sido denegada en el Banco Emisor</div>
                    @endif
                    @if($authorizationResult == 05 || $authorizationResult == "Failure")
                            <div class="alert alert-danger">La operación ha sido rechazada</div>
                    @endif

                    <div class="header-receipt {!! ($authorizationResult == 00 || $authorizationResult == "Success") ? 'ok' : 'error' !!}">
                        <h2 class="header-receipt-number">Numero de operación: {!! $purchaseOperationNumber !!}</h2>
                        <h3 class="header-receipt-status">Estado:
                            @if($authorizationResult == 00 || $authorizationResult == "Success")
                                <span>Autorizada</span>
                            @endif
                            @if($authorizationResult == 01)
                                <span>Denegada en el Banco Emisor</span>
                            @endif
                            @if($authorizationResult == 05 || $authorizationResult == "Failure")
                                <span>Rechazada</span>
                            @endif
                        </h3>
                    </div>
                @endif
                <div class="form">
                    @if(isset($items))
                        <div class="table-responsive payment__options-table">

                            <table class="table table-striped  table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Precio</th>
                                </tr>
                                </thead>
                                <tbody>

                             
                                    <tr>
                                        <td>{{ $income->description }}</td>
                                        <td> {{ money($income->amount) }}</td>
                                    </tr>
                              

                                </tbody>

                            </table>
                            <h1 class="payment__title">Total: {{ money($total) }} </h1>
                        </div>
                    @endif


                </div>
            </section>






    </div>
		
@endsection
@section('scripts')
<script src="/js/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/js/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script>
  $(function () {
  
    $("[data-mask]").inputmask();
  });
</script>
@endsection

