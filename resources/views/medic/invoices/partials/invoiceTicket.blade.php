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