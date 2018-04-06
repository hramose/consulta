
    
        <!-- info row -->
        <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <div class="logo">
            <img src="{{ getLogo($invoice->clinic) }}" alt="logo">
          </div>  
        

          
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h2>{{ $configFactura->nombre_comercial }}</h2>
          <address>
         
        
            @if($invoice->clinic->type == 'Consultorio Independiente')
              @if($clinic->bill_to == 'C')
                <b>Ced. Jurídica:</b> {{ $clinic->ide }}<br>
                <b>Nombre:</b> {{ $clinic->ide_name }}
              @else 
                <b>Ced:</b> {{ $invoice->user->ide }}<br>
                <b>Nombre:</b> {{ $invoice->user->name }}
              @endif
          @else
              @if($invoice->bill_to == 'M')
              <b>Ced:</b> {{ $invoice->user->ide }}<br>
              <b>Nombre:</b> {{ $invoice->user->name }}
              @else 
              <b>Ced. Jurídica:</b> {{ $invoice->clinic->ide }}<br>
              <b>Nombre:</b> {{ $invoice->clinic->ide_name }}
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
        <div class="col-xs-4 invoice-col invoice-left">     
            <b>Cliente:</b> {{ $invoice->client_name }}<br>
              {{ $invoice->client_email }}<br>
        </div>
        <div class="col-xs-4 invoice-col invoice-right">
            
        </div>
        <div class="col-xs-4 invoice-col invoice-right">
            <b>Médico:</b> {{ $invoice->user->name }}<br>
            @foreach($invoice->user->specialities as $speciality)
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
     