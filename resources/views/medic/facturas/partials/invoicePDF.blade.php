<div id="html-pdf">
        

            <table id="tableHtml">
					<tr>
							<td>
									
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							<div class="logo" style="background-position: center center;background-size: contain;background-repeat: no-repeat;height: 90px;">
								<img src="{{ getLogo($factura->clinic) }}" alt="logo" style="height: 90px;">
							</div>  
						
			
							
						</div>
					
									
							
							</td>
							<td>
										
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							<h2>{{ $factura->clinic->name }}</h2>
                            <address>
                            {{ $factura->clinic->type }}<br>
                            {{ $factura->clinic->canton }}, {{ $factura->clinic->province }}<br>
                            {{ $factura->clinic->address }}<br>
                            <b>Tel:</b> {{ $factura->clinic->phone }}<br>
                           
                            <b>Ced:</b> {{ $factura->user->configFactura->identificacion }}<br>
                            <b>Nombre:</b> {{ $factura->user->configFactura->nombre }}
                            
                            
                            </address>
						</div>
							
							</td>
							<td>
									
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							
                             <div class="invoice-number">
                                <h3>{{ trans('utils.tipo_documento.'.$factura->tipo_documento) }}:</h3>
                                <span>{{$factura->consecutivo_hacienda }}</span>
                                
                            </div>
                             <span>{{ $factura->clave_fe }}</span> <br> 
                             <span><b>Condicion venta:</b> {{ trans('utils.condicion_venta.'.$factura->condicion_venta) }}</span> <br>  
                             <span><b>Medio Pago:</b> {{ trans('utils.medio_pago.'.$factura->medio_pago) }}</span>  <br> 
                           
                            
                           
                            <b>Fecha emisión:</b> {{ $factura->created_at }}<br> 
                           
          
							
						
							
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
                             @if($factura->appointment)         
							 <b>Paciente:</b> {{ $factura->appointment->patient->fullname }}<br>
                            {{ $factura->appointment->patient->address }}<br>
                            @else 
                              <b>Cliente:</b> {{ $factura->client_name }}<br>
                                {{ $factura->client_email }}<br>
                            @endif

					</div>
								
							</td>
							<td>
							
							</td>
							<td>
								
							<div class="col-xs-4 invoice-col invoice-right" style="text-align: right;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
                              @if($factura->appointment)   
                                <b>Médico:</b> {{ $factura->user->name }}<br>
                                @foreach($factura->user->specialities as $speciality)
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
                                @foreach($factura->lines as $line)
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
          
                           
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                @include('user.facturas.partials.notaHacienda')
                            </p>
                           
                            </div>
                        </td>
                        <td style="padding-top:15px;">
                            <table class="table">
             
                                <tr>
                                    <th><b>Total:</b>   </th>
                                    <td>{{ money($factura->total, '&cent;') }}</td>
                                </tr>
                                <tr>
                                    <th><b>Pago con:</b> </th>
                                    <td>{{ money($factura->pay_with, '&cent;') }}</td>
                                </tr>
                                <tr>
                                    <th><b>Vuelto:</b></th>
                                    <td>{{ money($factura->change, '&cent;') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
				
			</table>
        </div>