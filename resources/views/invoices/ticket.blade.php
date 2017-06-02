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
            {{ $invoice->medic->name }}<br>
            {{ $invoice->clinic->address }}<br>
            Tel: {{ $invoice->clinic->phone }}<br>
            Factura #{{$invoice->id }}<br>
            Fecha: {{ \Carbon\Carbon::now() }}<br>
            Cliente: {{ $invoice->client_name }}
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
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onclick="printSummary();"><i class="fa fa-print"></i> Print</a>
           <a href="/medic/appointments/{{ $invoice->appointment->id}}/edit" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Regresar a consulta
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
 </script>
 @endsection