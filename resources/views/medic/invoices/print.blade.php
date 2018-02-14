@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
 <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
@endsection
@section('content')
 <section class="content">
 
		 <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      @if($invoice->fe && $invoice->status_fe != 'aceptado')  
        <div class="row">
          <div class="col-xs-12">
          
              <div class="callout callout-warning">
                <h4>Información importante!</h4>

                <p>Estado: {{ $invoice->status_fe }}. Parece que la factura aun no ha sido aprobada por Hacienda. Puedes verificar desde este enlace por que no ha sido aprobada <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Comprobar estado de factura" data-invoice="{{ $invoice->id }}"><b>Comprobar estado de factura</b></a>
              
              </p>
              </div>
              
          
          </div>
          <!-- /.col -->
        </div>
       @endif
        <!-- info row -->
        <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <div class="logo">
            <img src="{{ getLogo($invoice->clinic) }}" alt="logo">
          </div>  
        

          
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h2>{{ $invoice->clinic->name }}</h2>
          <address>
          {{ $invoice->clinic->type }}<br>
          {{ $invoice->clinic->canton }}, {{ $invoice->clinic->province }}<br>
          {{ $invoice->clinic->address }}<br>
          <b>Tel:</b> {{ $invoice->clinic->phone }}<br>
          @if($invoice->fe)
            <b>Ced:</b> {{ $invoice->medic->configFactura->identificacion }}<br>
            <b>Nombre:</b> {{ $invoice->medic->configFactura->nombre }}
          @else 
               @if($invoice->clinic->type == 'Consultorio Independiente')
                  @if($invoice->clinic->bill_to == 'C')
                   <b>Ced. Jurídica:</b> {{ $invoice->clinic->ide }}<br>
                   <b>Nombre:</b> {{ $invoice->clinic->ide_name }}
                  @else 
                   <b>Ced:</b> {{ $invoice->medic->ide }}<br>
                    <b>Nombre:</b> {{ $invoice->medic->name }}
                  @endif
              @else
                  @if($invoice->bill_to == 'C')
                   <b>Ced. Jurídica:</b> {{ $invoice->clinic->ide }}<br>
                   <b>Nombre:</b> {{ $invoice->clinic->ide_name }}
                  @else 
                    <b>Ced:</b> {{ $invoice->medic->ide }}<br>
                    <b>Nombre:</b> {{ $invoice->medic->name }}
                  @endif
                  
              @endif

          @endif
         
          </address>
          
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <div class="invoice-number">
            <h3>Nro. Factura:</h3>
            <h4>{{$invoice->consecutivo }}</h4>
            
          </div>
          <div> <span>Contado</span>  </div>
         
          <div class="invoice-date">
          <b>Fecha:</b> {{ $invoice->created_at }}
          </div>
          
          
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <hr>
      <div class="row invoice-patient">
        <div class="col-xs-4 invoice-col invoice-left">     
            <b>Paciente:</b> {{ $invoice->appointment->patient->fullname }}<br>
            {{ $invoice->appointment->patient->address }}<br>
        </div>
        <div class="col-xs-4 invoice-col invoice-right">
            
        </div>
        <div class="col-xs-4 invoice-col invoice-right">
            <b>Médico:</b> {{ $invoice->medic->name }}<br>
            @foreach($invoice->medic->specialities as $speciality)
              {{ $speciality->name }} 
            @endforeach
        </div>
      </div>
      <hr>

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Cantidad</th>
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
        <div class="col-xs-6">
          
        
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          

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
 <script>
    $(".dropdown-toggle").dropdown();
   $('#modalRespHacienda').on('shown.bs.modal', function (event) {
     
      var button = $(event.relatedTarget) // Button that triggered the modal
      var invoiceId = button.attr('data-invoice') // Extract info from data-* attributes
      $('.loader').show();
      $("#resp-clave").text('')
      $("#resp-emisor").text('')
      $("#resp-receptor").text('')
      $("#resp-mensaje").text('')
      $("#resp-detalle").text('')
       $.ajax({
          type: 'GET',
          url: '/medic/invoices/'+ invoiceId +'/recepcion',
          data: {_token: $('meta[name="csrf-token"]').content},
          success: function (resp) {
             $('.loader').hide();
            var respHacienda = JSON.parse(resp.resp_hacienda);
            $("#resp-clave").text(respHacienda.Clave)
            $("#resp-emisor").text(respHacienda.NombreEmisor)
            $("#resp-receptor").text(respHacienda.NombreReceptor)
            $("#resp-mensaje").text(respHacienda.Mensaje)
            $("#resp-detalle").text(respHacienda.DetalleMensaje)
            
          },
          error: function () {
            console.log('error finalizando citan');

          }

        });
     
    });

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