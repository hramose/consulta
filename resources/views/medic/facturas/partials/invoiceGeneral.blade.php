
      @if($factura->fe && $factura->status_fe != 'aceptado')  
        <div class="row">
          <div class="col-xs-12">
          
              <div class="callout callout-warning">
                <h4>Información importante!</h4>
                @if($factura->status_fe)
                  <p>Estado: {{ $factura->status_fe }}. Parece que la factura aun no ha sido aprobada por Hacienda. Puedes verificar desde este enlace por que no ha sido aprobada <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Comprobar estado de factura" data-factura="{{ $factura->id }}"><b>Comprobar estado de factura</b></a>
                @else 
                    <p>Parece hubo un problema en la conexion con hacienda y la factura no pudo ser enviada. Puedes tratar de reeviarla desde el panel de facturación en el siguiente enlace <a href="/user/facturas" title="Panel de facturacion"><b>Facturación</b></a>
                @endif
              
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
            <img src="{{ getLogo($factura->clinic) }}" alt="logo">
          </div>  
        

          
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h2>{{ $configFactura->nombre_comercial }}</h2>
          <address>
         
          @if($factura->fe)
          
            <b>Tel:</b> {{ $configFactura->telefono }}<br>
            <b>Ced:</b> {{ $configFactura->identificacion }}<br>
            <b>Nombre:</b> {{ $configFactura->nombre }}
          @else 
               @if($factura->clinic->type == 'Consultorio Independiente')
                  @if($clinic->bill_to == 'C')
                   <b>Ced. Jurídica:</b> {{ $clinic->ide }}<br>
                   <b>Nombre:</b> {{ $clinic->ide_name }}
                  @else 
                   <b>Ced:</b> {{ $factura->user->ide }}<br>
                    <b>Nombre:</b> {{ $factura->user->name }}
                  @endif
              @else
                  @if($factura->bill_to == 'M')
                  <b>Ced:</b> {{ $factura->user->ide }}<br>
                  <b>Nombre:</b> {{ $factura->user->name }}
                  @else 
                  <b>Ced. Jurídica:</b> {{ $factura->clinic->ide }}<br>
                  <b>Nombre:</b> {{ $factura->clinic->ide_name }}
                  @endif
                  
              @endif

          @endif
         
          </address>
          
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          @if($factura->fe)
          <div class="invoice-number">
            <h5>{{ trans('utils.tipo_documento.'.$factura->tipo_documento) }}:</h5>
            <h6>{{$factura->consecutivo_hacienda }}</h6>
            
          </div>
          <div> <span>{{ $factura->clave_fe }}</span>  </div>
          <div> <span><b>Condicion venta:</b> {{ trans('utils.condicion_venta.'.$factura->condicion_venta) }}</span>  </div>
          <div> <span><b>Medio Pago:</b> {{ trans('utils.medio_pago.'.$factura->medio_pago) }}</span>  </div>
          @else
            <div class="invoice-number">
              <h3>Nro. Factura:</h3>
              <h4>{{$factura->consecutivo }}</h4>
              
            </div>
             <div> <span><b>Condicion venta:</b> {{ trans('utils.condicion_venta.'.$factura->condicion_venta) }}</span>  </div>
             <div> <span><b>Medio Pago:</b> {{ trans('utils.medio_pago.'.$factura->medio_pago) }}</span>  </div>
          @endif
         
          <div class="invoice-date">
          <b>Fecha emisión:</b> {{ $factura->created_at }}
          </div>
          
          
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <hr>
      <div class="row invoice-patient">
        <div class="col-xs-4 invoice-col invoice-left">     
            <b>Cliente:</b> {{ $factura->client_name }}<br>
              {{ $factura->client_email }}<br>
        </div>
        <div class="col-xs-4 invoice-col invoice-right">
            
        </div>
        <div class="col-xs-4 invoice-col invoice-right">
            <b>Médico:</b> {{ $factura->user->name }}<br>
            @foreach($factura->user->specialities as $speciality)
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
        <div class="col-xs-6">
          
         @if($factura->fe)
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
           @include('medic.facturas.partials.notaHacienda')
          </p>
          @endif
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          

          <div class="table-responsive">
            <table class="table">
             
              <tr>
                <th>Total:</th>
                <td>{{ money($factura->total) }}</td>
              </tr>
              
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
     