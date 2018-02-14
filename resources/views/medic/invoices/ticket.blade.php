@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')

	
		 <!-- Main content -->
    <section class="invoice invoice-ticket">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> {{ config('app.name', 'Laravel') }}
            
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-12 ">
          
          <address>
            <strong>{{ $invoice->clinic->name }}</strong><br>
            @if($invoice->fe)
              Ced: {{ $invoice->medic->configFactura->identificacion }}<br>
              Nombre: {{ $invoice->medic->configFactura->nombre }}
            @else 
                @if($invoice->clinic->type == 'Consultorio Independiente')
                    @if($invoice->clinic->bill_to == 'C')
                      Ced. Jurídica: {{ $invoice->clinic->ide }}<br>
                      Nombre: {{ $invoice->clinic->ide_name }}
                    @else 
                      Ced: {{ $invoice->medic->ide }}<br>
                      Nombre: {{ $invoice->medic->name }}
                    @endif
                @else
                    @if($invoice->bill_to == 'C')
                      Ced. Jurídica: {{ $invoice->clinic->ide }}<br>
                      Nombre: {{ $invoice->clinic->ide_name }}
                    @else 
                      Ced: {{ $invoice->medic->ide }}<br>
                      Nombre: {{ $invoice->medic->name }}
                    @endif
                    
                @endif

            @endif
            {{ $invoice->clinic->address }}<br>
            Tel: {{ $invoice->clinic->phone }}<br>
            Factura Contado #{{$invoice->consecutivo }}<br>
            Fecha: {{ \Carbon\Carbon::now() }}<br>
            Cliente: {{ $invoice->client_name }}<br>
            Médico: {{ $invoice->medic->name }}<br>
          </address>
        </div>
        <!-- /.col -->
        
       
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
            <th>Cant</th>
	          <th>Servicio</th>
	          <th>Precio</th>
	          <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->lines as $line)
            <tr>
              <td>{{ $line->quantity }}</td>
              <td>{{ $line->service }}</td>
              <td>{{ money($line->amount) }}</td>
              <td>{{ money($line->total_line) }}</td>
             
            </tr>
            @endforeach
            
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        
        <div class="col-xs-12">
          

          <div class="table-responsive">
            <table class="table">
             
              <tr>
                <th>Total:</th>
                <td>{{ money($invoice->total) }}</td>
              </tr>
              <tr>
                <th>Pago con:</th>
                <td>{{ money($invoice->pay_with) }}</td>
              </tr>
              <tr>
                <th>Vuelto:</th>
                <td>{{ money($invoice->change) }}</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printSummary();"><i class="fa fa-print"></i> Imprimir</a>
           <a href="/medic/appointments/{{ $invoice->appointment->id}}/edit" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Regresar a consulta
          </a>
            <a href="/medic/invoices" class="btn btn-info pull-right"><i class="fa fa-credit-card"></i> Regresar a facturación
          </a>
          <a href="#" class="btn btn-info btn-finish-appointment pull-right "><i class="fa fa-credit-card"></i> Terminar Consulta
          </a>
         
        </div>
      </div>
    </section>
    <!-- /.content -->
		

 

 @endsection
 @section('scripts')
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