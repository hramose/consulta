@extends('layouts.app')
@section('css')
<style>
	  
	  #tableHtml{
			display:none;
		}

</style>
@endsection
@section('content')
 <section class="content">
 
		 <!-- Main content -->
    <section class="invoice">
      <form action="/medic/invoices/{{ $invoice->id }}/pdf" method="POST" id="form-generate-pdf">
		 		<input type="hidden" name="htmltopdf" value="" id="htmltopdf">
				<button type="submit" class="btn btn-primary btn-lg">Descargar PDF</button>
	  </form>
       @include('medic/invoices/partials/invoice')
       @include('medic/invoices/partials/invoicePDF')

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printSummary();"><i class="fa fa-print"></i> Imprimir</a>
         

           <a href="/medic/invoices" class="btn btn-info pull-right"><i class="fa fa-credit-card"></i> Regresar a facturación
          </a>
          <a href="#" class="btn btn-default btn-finish-appointment pull-right "><i class="fa fa-check"></i> Terminar Consulta
          </a>
         
         
        </div>
      </div>
    </section>
    <!-- /.content -->
		

</section>
 @endsection
 @section('scripts')


 <script>

 function generatePDF() {
            $('#htmltopdf').val($('#html-pdf').html())
						$('#form-generate-pdf').submit();
        }
 
        window.onload = generatePDF;
        
 </script>
 @endsection