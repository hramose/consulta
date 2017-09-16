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
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <div class="invoice-number">
            <h3>Nro. Factura:</h3>
            <h4>{{$invoice->id }}</h4>
          </div>  
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
		

 
</section>
 @endsection
 @section('scripts')
 <script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
 <script>

 	 function printSummary() {
            window.print();
        }
        window.onload = printSummary;

     $('.btn-finish-appointment').on('click', function (e) {
        
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

               window.location = '/medic/appointments';
            }
          })
    });
 </script>
 @endsection