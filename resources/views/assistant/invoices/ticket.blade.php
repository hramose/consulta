@extends('layouts.app-assistant')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')

	
		 <!-- Main content -->
    <section class="invoice invoice-ticket">
      
       @include('medic/invoices/partials/invoiceTicket')

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printSummary();"><i class="fa fa-print"></i> Imprimir</a>
         
          <a href="/assistant/invoices" class="btn btn-info pull-right"><i class="fa fa-credit-card"></i> Regresar a facturas general
          </a>

          <a href="/assistant/medics/{{$invoice->medic->id }}/invoices" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Regresar a facturas del médico
          </a>
         
        </div>
      </div>
    </section>
    <!-- /.content -->
		

 
 @if($invoice->fe)
    @include('medic/invoices/partials/status-hacienda-modal')
@endif


 @endsection
 @section('scripts')
  <script src="/js/bootstrap.min.js"></script>
  <script src="{{ elixir('/js/modalRespHacienda.min.js') }}"></script>
 <script>

 	 function printSummary() {
            window.print();
        }
        window.onload = printSummary;
 </script>
 @endsection