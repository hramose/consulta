
      @if($invoice->status_fe != 'aceptado')  
        <div class="row">
          <div class="col-xs-12">
          
              <div class="callout callout-warning">
                <h4>Información importante!</h4>
                @if($invoice->status_fe)
                  <p>Estado: {{ $invoice->status_fe }}. Parece que la factura aun no ha sido aprobada por Hacienda. Puedes verificar desde este enlace por que no ha sido aprobada <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Comprobar estado de factura" data-invoice="{{ $invoice->id }}"><b>Comprobar estado de factura</b></a>
                @else 
                    <p>Parece hubo un problema en la conexion con hacienda y la factura no pudo ser enviada. Puedes tratar de reeviarla desde el panel de facturación en el siguiente enlace <a href="/medic/facturas" title="Panel de facturacion"><b>Facturación</b></a>
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
  
            <b>Ced:</b> {{ $invoice->obligadoTributario->identificacion }}<br>
            <b>Nombre:</b> {{ $invoice->obligadoTributario->nombre }}
          
         
          </address>
          
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
         
          <div class="invoice-number">
             <h5> <b>{{ $invoice->tipo_documento_name }} :</b></h5>
            <h6>{{$invoice->consecutivo_hacienda }}</h6>
            
          </div>
          <div> <span>{{ $invoice->clave_fe }}</span>  </div>
          <div> <span><b>Condicion venta:</b> {{ trans('utils.condicion_venta.'.$invoice->condicion_venta) }}</span>  </div>
          <div> <span><b>Medio Pago:</b> {{ trans('utils.medio_pago.'.$invoice->medio_pago) }}</span>  </div>
         
         
          <div class="invoice-date">
          <b>Fecha emisión:</b> {{ $invoice->created_at }}
          </div>
          
          
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <hr>
      <div class="row invoice-patient">
        @if($invoice->appointment)     
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
        @else 

           <div class="col-xs-4 invoice-col invoice-left">
              
              <b>Cliente:</b> {{ $invoice->client_name }}<br>
              {{ $invoice->client_email }}<br>
          </div>
          <div class="col-xs-4 invoice-col invoice-right">
              
          </div>
          <div class="col-xs-4 invoice-col invoice-right">
           
          </div>

        @endif
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
         
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            @include('medic.invoices.partials.notaHacienda')
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
              <!-- <tr>
                <th>Pago con:</th>
                <td>{{ money($invoice->pay_with) }}</td>
              </tr>
              <tr>
                <th>Vuelto:</th>
                <td>{{ money($invoice->change) }}</td>
              </tr> -->
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
       @if($invoice->documentosReferencia->count())
      <div class="row">
          <h2>Documentos de referencia</h2>
          <div class="col-xs-12 table-responsive">
            @include('medic/invoices/partials/referencias')
          </div>
      </div>
      @endif
     