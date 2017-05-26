@extends('layouts.app-assistant')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')
 <section class="content">
	
		 <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> {{ config('app.name', 'Laravel') }}
            <small class="pull-right">Fecha: {{ \Carbon\Carbon::now() }}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          
          <address>
            <strong>{{ $invoice->clinic->name }}</strong><br>
            {{ $invoice->clinic->type }}<br>
            {{ $invoice->clinic->address }}<br>
            {{ $invoice->clinic->province }}<br>
           <b>Tel:</b> {{ $invoice->clinic->phone }}<br>
            
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
       
          <address>
            <strong>{{ $invoice->client_name }}</strong><br>
            {{ $invoice->appointment->patient->address }}<br>
            {{ $invoice->appointment->patient->province }}<br>
            Tel: {{ $invoice->appointment->patient->phone }}<br>
            Email: {{ $invoice->appointment->patient->email }}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Factura #{{$invoice->id }}</b><br>
          <b>Fecha:</b> {{ $invoice->created_at }}<br>
          <b>Médico:</b> {{ $invoice->medic->name }}<br>
           @foreach($invoice->medic->specialities as $speciality)
           	{{ $speciality->name }},
           @endforeach
          
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
          
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
             Fecha Consulta: {{ $invoice->appointment->date }}
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          

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
          <a href="/assistant/invoices" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Regresar a facturas
          </a>
         
        </div>
      </div>
    </section>
    <!-- /.content -->
		

 
</section>
 @endsection
 @section('scripts')
 <script>

 	 function printSummary() {
            window.print();
        }
        window.onload = printSummary;
 </script>
 @endsection