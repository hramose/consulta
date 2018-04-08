@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')

	
		 <!-- Main content -->
    <section class="invoice invoice-ticket">

      @include('medic/facturas/partials/invoiceTicket')
      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printSummary();"><i class="fa fa-print"></i> Imprimir</a>
          
            <a href="/medic/facturas" class="btn btn-info pull-right"><i class="fa fa-credit-card"></i> Regresar a facturación
          </a>
          
         
        </div>
      </div>
    </section>
    <!-- /.content -->
		


    @include('medic/facturas/partials/status-hacienda-modal')


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