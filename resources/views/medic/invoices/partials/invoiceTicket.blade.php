    @if($invoice->fe && $invoice->status_fe != 'aceptado')  
        <div class="row">
          <div class="col-xs-12">
          
              <div class="callout callout-warning">
                <h4>Información importante!</h4>

                 @if($invoice->status_fe)
                  <p>Estado: {{ $invoice->status_fe }}. Parece que la factura aun no ha sido aprobada por Hacienda. Puedes verificar desde este enlace por que no ha sido aprobada <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Comprobar estado de factura" data-invoice="{{ $invoice->id }}"><b>Comprobar estado de factura</b></a>
                @else 
                    <p>Parece hubo un problema en la conexion con hacienda y la factura no pudo ser enviada. Puedes tratar de reeviarla desde el panel de facturación en el siguiente enlace <a href="/medic/invoices" title="Panel de facturacion"><b>Facturación</b></a>
                @endif
              
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
          
          <address style="word-wrap: break-word;">
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
                    @if($invoice->bill_to == 'M')
                    Ced: {{ $invoice->medic->ide }}<br>
                    Nombre: {{ $invoice->medic->name }}
                    @else 
                    Ced. Jurídica: {{ $invoice->clinic->ide }}<br>
                    Nombre: {{ $invoice->clinic->ide_name }}
                    @endif
                    
                @endif

            @endif
            {{ $invoice->clinic->address }}<br>
            Tel: {{ $invoice->clinic->phone }}<br>
             @if($invoice->fe)
            
              {{ trans('utils.tipo_documento.'.$invoice->tipo_documento) }}:<br>
              {{$invoice->consecutivo_hacienda }}
              
            
            {{ $invoice->clave_fe }}<br>
            Condicion venta:{{ trans('utils.condicion_venta.'.$invoice->condicion_venta) }}<br>
            Medio Pago: {{ trans('utils.medio_pago.'.$invoice->medio_pago) }}<br>
            @else
             Nro. Factura: {{$invoice->consecutivo }}<br>
             Condicion venta: {{ trans('utils.condicion_venta.'.$invoice->condicion_venta) }}<br>
             Medio Pago: {{ trans('utils.medio_pago.'.$invoice->medio_pago) }}<br>
            @endif
            Fecha emisión: {{ $invoice->created_at }}<br>
            Cliente: {{ $invoice->client_name }}<br>
            @if($invoice->appointment)    
              Médico: {{ $invoice->medic->name }}<br>
            @endif
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
              <td>{{ $line->name }}</td>
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
       <div class="row">
        <!-- accepted payments column -->
        
        <div class="col-xs-12">
            @if($invoice->fe)
            <p>@include('medic.invoices.partials.notaHacienda')</p>
           @endif
        </div>
        <!-- /.col -->
      </div>