    @if($factura->status_fe != 'aceptado')  
        <div class="row">
          <div class="col-xs-12">
          
              <div class="callout callout-warning">
                <h4>Información importante!</h4>

                 @if($factura->status_fe)
                  <p>Estado: {{ $factura->status_fe }}. Parece que la factura aun no ha sido aprobada por Hacienda. Puedes verificar desde este enlace por que no ha sido aprobada <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Comprobar estado de factura" data-invoice="{{ $factura->id }}"><b>Comprobar estado de factura</b></a>
                @else 
                    <p>Parece hubo un problema en la conexion con hacienda y la factura no pudo ser enviada. Puedes tratar de reeviarla desde el panel de facturación en el siguiente enlace <a href="/user/facturas" title="Panel de facturacion"><b>Facturación</b></a>
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
            <strong>{{ $factura->clinic->name }}</strong><br>
           
              Ced: {{ $configFactura->identificacion }}<br>
              Nombre: {{ $configFactura->nombre }}
           
            {{ $factura->clinic->address }}<br>
            Tel: {{ $factura->clinic->phone }}<br>
           
            
              {{ trans('utils.tipo_documento.'.$factura->tipo_documento) }}:<br>
              {{$factura->consecutivo_hacienda }}
              
            
            {{ $factura->clave_fe }}<br>
            Condicion venta:{{ trans('utils.condicion_venta.'.$factura->condicion_venta) }}<br>
            Medio Pago: {{ trans('utils.medio_pago.'.$factura->medio_pago) }}<br>
           
            Fecha emisión: {{ $factura->created_at }}<br>
            Cliente: {{ $factura->client_name }}<br>
            @if($factura->appointment)    
              Médico: {{ $factura->user->name }}<br>
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
            @foreach($factura->lines as $line)
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
                <td>{{ money($factura->total) }}</td>
              </tr>
              <tr>
                <th>Pago con:</th>
                <td>{{ money($factura->pay_with) }}</td>
              </tr>
              <tr>
                <th>Vuelto:</th>
                <td>{{ money($factura->change) }}</td>
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
           
            <p>@include('medic.facturas.partials.notaHacienda')</p>
          
        </div>
        <!-- /.col -->
      </div>