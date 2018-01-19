@extends('layouts.app')

@section('content')

@include('layouts/partials/header-pages',['page'=>'Comprobante de pago'])
@if(isset($authorizationResult) && isset($purchaseOperationNumber))

    @if($authorizationResult == 00 || $authorizationResult == "Success")
        <div id="infoBox" class="alert alert-info">Pago realizado con exito</div>
    @endif
    @if($authorizationResult == 01)
            <div id="infoBox" class="alert alert-danger">La operación ha sido denegada en el Banco Emisor</div>
    @endif
    @if($authorizationResult == 05 || $authorizationResult == "Failure")
            <div id="infoBox" class="alert alert-danger">La operación ha sido rechazada</div>
    @endif

            
@endif
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> {{ config('app.name', 'Laravel') }}
            <small class="pull-right">{{ \Carbon\Carbon::now()->toDateTimeString() }}	</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
        @if(isset($authorizationResult) && isset($purchaseOperationNumber))
          <b>Numero de operación: {{ $purchaseOperationNumber }}</b><br>
          <br>
          <b>Estado:
            @if($authorizationResult == 00 || $authorizationResult == "Success")
                <span>Autorizada</span>
            @endif
            @if($authorizationResult == 01)
                <span>Denegada en el Banco Emisor</span>
            @endif
            @if($authorizationResult == 05 || $authorizationResult == "Failure")
                <span>Rechazada</span>
            @endif<br>
        @endif
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    @if(isset($income) && $income)
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Cant.</th>
              <th>Description</th>
              <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
        
            <tr>
                <td>1</td>
                <td>{{ $income->description }}</td>
                <td>{{ money($income->amount) }}</td>
            </tr>
            
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
         
          <div class="table-responsive">
            <table class="table">
              <tbody>
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td>{{ money($income->amount) }}</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>{{ money($total) }}</td>
              </tr>
            </tbody></table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    @endif
      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printComprobante();"><i class="fa fa-print"></i> Imprimir</a>
           <a href="/medic/appointments" class="btn btn-success pull-right"><i class="fa fa-edit"></i> Regresar a Agenda
          </a>
        </div>
      </div>
    </section>
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
                    @if(isset($income) && $income)
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
<script>
function printSummary() {
            window.print();
        }
  
</script>
@endsection

