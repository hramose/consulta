@extends('layouts.app-clinic')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
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
           @if($invoice->clinic->type == 'Consultorio Independiente')
                @if($invoice->clinic->facturar)
                  Ced. Jurídica: {{ $invoice->clinic->ide }}
                @else 
                  Ced: {{ auth()->user()->ide }}
                @endif
            @else 
                Ced. Jurídica: {{ $invoice->clinic->ide }}
            @endif
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <div class="invoice-number">
            <h3>Nro. Factura:</h3>
            <h4>{{$invoice->id }}</h4>
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
            <b>Paciente:</b> {{ $invoice->client_name }}<br>
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

          <a href="/clinic/patients" class="btn btn-info pull-right"><i class="fa fa-credit-card"></i> Regresar a pacientes
          </a>

          <a href="/clinic/patients/{{$invoice->patient_id }}/invoices" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Regresar a facturas del paciente
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