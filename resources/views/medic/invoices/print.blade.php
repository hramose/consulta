@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
 <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
@endsection
@section('content')
 <section class="content">
 
		 <!-- Main content -->
    <section class="invoice">
      
      @include('medic/invoices/partials/invoice')

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printSummary();"><i class="fa fa-print"></i> Imprimir</a>
          <a href="/medic/appointments/{{ $invoice->appointment->id}}/edit" class="btn btn-success pull-right"><i class="fa fa-edit"></i> Regresar a consulta
          </a>

           <a href="/medic/invoices" class="btn btn-info pull-right"><i class="fa fa-credit-card"></i> Regresar a facturación
          </a>
          <a href="#" class="btn btn-default btn-finish-appointment pull-right "><i class="fa fa-check"></i> Terminar Consulta
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
 <script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
 <script src="{{ elixir('/js/modalRespHacienda.min.js') }}"></script>
 <script>

 	 function printSummary() {
            window.print();
        }
        
        window.onload = printSummary;
        

     $('.btn-finish-appointment').on('click', function (e) {
        $.ajax({
          type: 'PUT',
          url: '/medic/appointments/{{ $invoice->appointment->id }}/finished',
          data: {},
          success: function (resp) {
            
            console.log('cita finalizada');
            swal({
              title: 'Terminando Consulta!',
              text: "Desea agendar un seguimiento a este paciente o volver a la agenda del día?",
              //type: 'info',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Agendar seguimiento',
              cancelButtonText: 'Volver a agenda',
              //confirmButtonClass: 'btn btn-success',
              //cancelButtonClass: 'btn btn-danger',
              //buttonsStyling: false
            }).then(function () {
              
              window.location = '/medic/appointments/create?p={{ $invoice->appointment->patient->id }}';
                  
                  
            }, function (dismiss) {
              // dismiss can be 'cancel', 'overlay',
              // 'close', and 'timer'
              if (dismiss === 'cancel') {
    
              window.location = '/medic/appointments?clinic={{ $invoice->office_id }}';
                  
              }else{
                window.location = '/medic/appointments/{{ $invoice->appointment->id }}/edit';
              }
            })
            
          },
          error: function () {
            console.log('error finalizando citan');

          }

        });
				
        
    });
 </script>
 @endsection