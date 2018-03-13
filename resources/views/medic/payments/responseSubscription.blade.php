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
      @if(auth()->user()->fe && !auth()->user()->configFactura->first())  
        <div class="row">
          <div class="col-xs-12">
          
              <div class="callout callout-warning">
                <h4>Información importante!</h4>

                <p>Haz seleccionado que vas a utilizar la factura electronica de hacienda. Por favor recuerda que debes configurar los parametros iniciales para funcionar correctamente. Puedes realizarlo desde este link. <a href="/medic/account/edit?tab=fe" title="Ir a configurar Factura Electrónica"><b>Configurar Factura Electrónica</b></a></p>
              </div>
              
          
          </div>
          <!-- /.col -->
        </div>
       @endif
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
        <div class="col-sm-6">
        @if(isset($authorizationResult) && isset($purchaseOperationNumber))
          <b>Numero de operación:</b> {{ $purchaseOperationNumber }}
          <br>
          <b>Estado:</b>
            @if($authorizationResult == 00 || $authorizationResult == "Success")
                <span class="text-green" ><b>Autorizada</b></span>
            @endif
            @if($authorizationResult == 01)
                <span class="text-red"><b>Denegada en el Banco Emisor</b></span>
            @endif
            @if($authorizationResult == 05 || $authorizationResult == "Failure")
                <span class="text-red"><b>Rechazada</b></span>
            @endif<br>
        @endif
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    @if(isset($plan) && $plan->count())
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
                <td>{{ $plan->title }}</td>
                <td>{{ money($plan->cost,'$') }}</td>
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
                <td>{{ money($total,'$') }}</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>{{ money($total,'$') }}</td>
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
          <a href="/medic/account/edit?tab=fe" title="Ir a configurar Factura Electronica" class="btn btn-danger pull-right"><i class="fa fa-edit"></i> Configurar Factura Electrónica
          </a>
        </div>
      </div>
    </section>
	
@endsection
@section('scripts')
<script>
function printComprobante() {
            window.print();
        }
  
</script>
@endsection

