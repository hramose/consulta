@extends('layouts.app-clinic')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')
 <section class="content">
	
		 <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
  
       @include('medic/invoices/partials/invoice')

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printSummary();"><i class="fa fa-print"></i> Imprimir</a>

          <a href="/clinic/patients" class="btn btn-info pull-right"><i class="fa fa-credit-card"></i> Regresar a pacientes
          </a>

          <a href="/clinic/patients/{{$invoice->patient_id }}/invoices" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Regresar a facturas del paciente
          </a>
         
        </div>
      </div>
    </section>
    <!-- /.content -->
		
@if($invoice->fe)
    @include('medic/invoices/partials/status-hacienda-modal')
@endif
 
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