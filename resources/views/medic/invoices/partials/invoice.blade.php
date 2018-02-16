
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