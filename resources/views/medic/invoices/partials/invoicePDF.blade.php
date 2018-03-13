<div id="html-pdf">
        

            <table id="tableHtml">
					<tr>
							<td>
									
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							<div class="logo" style="background-position: center center;background-size: contain;background-repeat: no-repeat;height: 90px;">
								<img src="{{ getLogo($invoice->clinic) }}" alt="logo" style="height: 90px;">
							</div>  
						
			
							
						</div>
					
									
							
							</td>
							<td>
										
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
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
                                    @if($invoice->bill_to == 'M')
                                    <b>Ced:</b> {{ $invoice->medic->ide }}<br>
                                    <b>Nombre:</b> {{ $invoice->medic->name }}
                                    @else 
                                    <b>Ced. Jurídica:</b> {{ $invoice->clinic->ide }}<br>
                                    <b>Nombre:</b> {{ $invoice->clinic->ide_name }}
                                    @endif
                                    
                                @endif

                            @endif
                            
                            </address>
						</div>
							
							</td>
							<td>
									
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							 @if($invoice->fe)
                             <div class="invoice-number">
                                <h3>{{ trans('utils.tipo_documento.'.$invoice->tipo_documento) }}:</h3>
                                <span>{{$invoice->consecutivo_hacienda }}</span>
                                
                            </div>
                             <span>{{ $invoice->clave_fe }}</span> <br> 
                             <span><b>Condicion venta:</b> {{ trans('utils.condicion_venta.'.$invoice->condicion_venta) }}</span> <br>  
                             <span><b>Medio Pago:</b> {{ trans('utils.medio_pago.'.$invoice->medio_pago) }}</span>  <br> 
                            @else
                               
                                <h3>Nro. Factura:</h3>
                                <h4>{{$invoice->consecutivo }}</h4>
                                
                                 <span><b>Condicion venta:</b> {{ trans('utils.condicion_venta.'.$invoice->condicion_venta) }}</span> <br>  
                                 <span><b>Medio Pago:</b> {{ trans('utils.medio_pago.'.$invoice->medio_pago) }}</span> <br> 
                            @endif
                            
                           
                            <b>Fecha emisión:</b> {{ $invoice->created_at }}<br> 
                           
          
							
						
							
						</div>
									
							
							</td>
					</tr>
					<tr>
							<td colspan="3">
							<hr>
							</td>	
					
					</tr>
					<tr>
							<td>
									  
							<div class="col-xs-4 invoice-col invoice-left" style="text-align: left;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                             @if($invoice->appointment)         
							 <b>Paciente:</b> {{ $invoice->appointment->patient->fullname }}<br>
                            {{ $invoice->appointment->patient->address }}<br>
                            @else 
                              <b>Cliente:</b> {{ $invoice->client_name }}<br>
                                {{ $invoice->client_email }}<br>
                            @endif

					</div>
								
							</td>
							<td>
							
							</td>
							<td>
								
							<div class="col-xs-4 invoice-col invoice-right" style="text-align: right;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                              @if($invoice->appointment)   
                                <b>Médico:</b> {{ $invoice->medic->name }}<br>
                                @foreach($invoice->medic->specialities as $speciality)
                                {{ $speciality->name }} 
                                @endforeach
                             @endif
					</div>
							</td>
					</tr>
					<tr>
							<td colspan="3">
							<hr>
							</td>	
					
					</tr>
					<tr>
						<td colspan="3">
                            <div>
                             <table class="table table-striped" >
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
                                <td>{{ money($line->amount, '&cent;') }}</td>
                                <td>{{ money($line->total_line,'&cent;') }}</td>
                                
                                </tr>
                                @endforeach
                                
                                </tbody>
                            </table>
                            </div>
                        </td>	
                    </tr>
                    <tr>
							<td colspan="3" >
							<hr>
							</td>	
					
					</tr>
                    <tr>
                        
                        <td colspan="2">
                            <div class="col-xs-6">
          
                            @if($invoice->fe)
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                @include('medic.invoices.partials.notaHacienda')
                            </p>
                            @endif
                            </div>
                        </td>
                        <td style="padding-top:15px;">
                            <table class="table">
             
                                <tr>
                                    <th><b>Total:</b>   </th>
                                    <td>{{ money($invoice->total, '&cent;') }}</td>
                                </tr>
                                <tr>
                                    <th><b>Pago con:</b> </th>
                                    <td>{{ money($invoice->pay_with, '&cent;') }}</td>
                                </tr>
                                <tr>
                                    <th><b>Vuelto:</b></th>
                                    <td>{{ money($invoice->change, '&cent;') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
				
			</table>
        </div>