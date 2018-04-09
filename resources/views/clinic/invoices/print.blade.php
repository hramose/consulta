@extends('layouts.app-clinic')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')
 <section class="content">
	
		 <!-- Main content -->
    <section class="invoice">
       @if($invoice->obligadoTributario)
        @include('medic/invoices/partials/invoiceHacienda')
        @include('medic/invoices/partials/status-hacienda-modal')
      @else
        @include('medic/invoices/partials/invoice')
      @endif
      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printSummary();"><i class="fa fa-print"></i> Imprimir</a>

          <a href="/clinic/invoices" class="btn btn-info pull-right"><i class="fa fa-credit-card"></i> Regresar a facturación
          </a>

         
        </div>
      </div>
    </section>
    <!-- /.content -->
		
</section>
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