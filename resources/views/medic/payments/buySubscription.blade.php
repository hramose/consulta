@extends('layouts.app')
@section('css')
    <script type="text/javascript" src="{{ env('URL_VPOS2')}}" ></script>
@endsection
@section('content')

@include('layouts/partials/header-pages',['page'=>'Realizar de pago'])

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
        <div class="col-sm-6">
        @if(isset($purchaseOperationNumber))
          <b>Numero de operación:</b> {{ $purchaseOperationNumber }}
          <br>
          <b>Estado:</b>
            
        @endif
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    @if(isset($newPlan) && $newPlan)
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
                <td>{{ $newPlan->title }}</td>
                <td>{{ money($newPlan->cost,'$') }}</td>
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
          <p class="lead">Payment Methods:</p>
          <img src="/img/credit/visa.png" alt="Visa">
          <img src="/img/credit/mastercard.png" alt="Mastercard">
          <!-- <img src="/img/credit/american-express.png" alt="American Express"> -->

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; max-width:350px;">
            <img src="/img/credit/banner_payme_latam.png" alt="Payme" style="width:100%">
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
         
          <div class="table-responsive">
            <table class="table">
              <tbody>
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td>{{ money($amountTotal,'$') }}</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>{{ money($amountTotal,'$') }}</td>
              </tr>
            </tbody></table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
   
      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
        <form method="POST" action="#" class="alignet-form-vpos2 form-horizontal">
            
                <input class="form-control" type="hidden" name ="acquirerId" value="{{ env('ACQUIRE_ID') }}" />
                <input class="form-control" type="hidden" name ="idCommerce" value="{{ env('COMMERCE_ID') }}" />
                <input class="form-control" type="hidden" name="purchaseOperationNumber" value="{{ $purchaseOperationNumber }}" />
                <input class="form-control" type="hidden" name="purchaseAmount" value="{{ $amount }}" />
                <input class="form-control" type="hidden" name="purchaseCurrencyCode" value="{{ $purchaseCurrencyCode }}" />
                <input class="form-control" type="hidden" name="language" value="SP" />
                <input class="form-control" type="hidden" name="shippingFirstName" value="{{ $medic_name }}" />
                <input class="form-control" type="hidden" name="shippingLastName" value="--" />
                <input class="form-control" type="hidden" name="shippingEmail" value="{{ $medic_email }}" />
                <input class="form-control" type="hidden" name="shippingAddress" value="Direccion" />
                <input class="form-control" type="hidden" name="shippingZIP" value="ZIP" />
                <input class="form-control" type="hidden" name="shippingCity" value="CITY" />
                <input class="form-control" type="hidden" name="shippingState" value="STATE" />
                <input class="form-control" type="hidden" name="shippingCountry" value="CR" />
                <input class="form-control" type="hidden" name="userCommerce" value="modalprueba1" />
                <input class="form-control" type="hidden" name="userCodePayme" value="8--580--4390" />
                <input class="form-control" type="hidden" name="descriptionProducts" value="{{ $description }}" />
                <input class="form-control" type="hidden" name="programmingLanguage" value="PHP" />
                <input class="form-control" type="hidden" name="reserved1" value="Valor Reservado ABC" />
                <input class="form-control" type="hidden" name="reserved2" value="{{ $newPlan->id }}" />
                
                <input class="form-control" type="hidden" name="purchaseVerification" value="{{ $purchaseVerification }}" />
                <input type="button" onclick="javascript:AlignetVPOS2.openModal('','2')" value="Realizar pago" class="btn btn-success pull-right">
              
                <!-- <button type="submit" class="btn btn-success btn-sm">Pagar</button> -->
              </form>
           
        </div>
      </div>
    @endif
    </section>
	
		
@endsection
@section('scripts')
<script>
function printComprobante() {
            window.print();
        }
  
</script>
@endsection

