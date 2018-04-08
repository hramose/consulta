@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
  <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 

@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
     
   
    
    <section class="content">
       
        <div class="row">
        
        <div class="col-md-12">
         
           <!-- <form method="POST" action="{{ url('/medic/balance') }}" class="form-horizontal">
                       {{ csrf_field() }}
                       <button type="submit" class="btn btn-danger">Ejecutar cierre de ventas</button>
                  </form> -->
       
          <h2>Facturas Electrónicas</h2>
          <div>
           
            <a href="/medic/no-invoices" class="btn btn-info">Ver consultas no facturadas</a>
            @if(auth()->user()->hasRole('medico'))
            <a href="/medic/facturas/create" class="btn btn-success">Crear Factura</a>
            @endif
         </div>
          <div class="box box-default box-calendar">
          <div class="box-header">
              <div class="">
                  <form action="/medic/facturas" method="GET" class="form-horizontal">
                       <div class="form-group">

                          <div class="col-sm-2">
                            <div class="input-group input-group-sm">
                          
                                
                                <input type="text" name="q" class="form-control" placeholder="Cliente..." value="{{ isset($search) ? $search['q'] : '' }}">
                                <div class="input-group-btn">

                                  <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>
                              
                              
                            </div>
                           
                          </div>


                          <div class="col-sm-2">
                            <div class="input-group input-group-sm">
                          
                                
                                <input type="text" name="date" class="date form-control" placeholder="Fecha..." value="{{ isset($search) ? $search['date'] : '' }}">
                                <div class="input-group-btn">

                                  <button type="submit" class="btn btn-primary">Buscar</button>
                                </div>
                              
                              
                            </div>
                           
                          </div>
                            <div class="col-sm-3">
                              <select name="clinic" id="clinic" class="form-control">
                                <option value="">Todas</option>
                              @foreach(auth()->user()->offices as $userClinic)
                                <option value="{{  $userClinic->id }}" {{ (isset($search) && $search['clinic'] == $userClinic->id) ? 'selected' : '' }}>{{  $userClinic->name }}</option>
                              @endforeach
                              <!-- <option value="0" {{ (isset($search) && $search['clinic'] == '0') ? 'selected' : '' }}>Por servicios a clínicas privadas</option> -->
                            </select>
                          
                          </div>
                      </div>
                      
                      </form>
              </div>
            <div class="pull-right"><span class="label label-success label-lg">Total: {{ money($totalFacturasAmount) }}</span>    </div> 
            </div>
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
                 <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Clínica</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Tipo Doc</th>
                        <th>Num. Consecutivo</th>
                        <th>Estado Hacienda</th>
                        <th>Generar NC</th>
                        <th>Generar ND</th>
                        <th>Ver XML</th>
                        <!-- <th>Ver PDF</th> -->
                      
                       
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($facturas as $factura)
                          <tr>
                            <td>{{ $factura->consecutivo }}</td>
                        
                            <td>
                             {{ $factura->created_at }}
                            </td>
                            <td>
                              @if($factura->clinic)
                                {{ $factura->clinic->name }}
                             @else 
                              --
                             @endif
                           
                            </td>
                            <td>
                             {{ $factura->client_name }}
                            
                            </td>
                           
                            <td>{{ money($factura->total) }}</span></td>
                            <!-- <td>
                                @if($factura->status)
                                  <span class="label label-success">Facturada</span>
                                @endif
                            </td> -->
                            
                           
                              <td>
                               
                                <span class="label label-{{ trans('utils.tipo_documento_color.'.$factura->tipo_documento) }}"> {{ trans('utils.tipo_documento.'.$factura->tipo_documento) }}</span>
                                
                              </td>
                              <td>
                                {{ $factura->consecutivo_hacienda }}
                              </td>
                            <td>
                             
                                  @if($factura->sent_to_hacienda)
                                    @if($factura->status_fe)
                                      <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Click para comprobar estado de factura" data-factura="{{ $factura->id }}"><span class="label label-{{ trans('utils.status_hacienda_color.'.$factura->status_fe) }}">{{ title_case($factura->status_fe) }}</span>   </a>
                                    @else
                                      <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Click para comprobar estado de factura" data-factura="{{ $factura->id }}"><span class="label label-warning">Comprobar</span>   </a>
                                    @endif
                                  @elseif($factura->status)
                                      
                                    <send-to-hacienda :factura-id="{{ $factura->id }}"></send-to-hacienda>
                                  @endif
                             
                            </td>
                            <td>
                             
                                <a href="/medic/facturas/{{ $factura->id }}/notacredito">Generar Nota Crédito</a>
                              
                            </td>
                            <td>
                             
                              <a href="/medic/facturas/{{ $factura->id }}/notadebito">Generar Nota Debito</a>
                             
                            </td>
                            <td>
                              
                              <a href="/medic/facturas/{{ $factura->id }}/download/xml">XML</a>
                             
                            </td>
                           
                            <td>
                              @if($factura->status)
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalInvoice" data-id="{{ $factura->id }}" data-medic="{{ $medic->id }}">
                                  <i class="fa fa-eye"></i> Detalle  
                                </button>
                                @else
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalInvoice" data-id="{{ $factura->id }}" data-medic="{{ $medic->id }}">
                                  <i class="fa fa-eye"></i> Facturar
                                </button>
                                @endif
                            </td>
                          </tr>
                      @endforeach
                      
                      </tbody>
                     
                      @if ($facturas)
                        <tfoot>
                            <tr>
                              <td  colspan="5" class="pagination-container">{!!$facturas->appends(['q' => $search['q'], 'date' => $search['date'], 'clinic' => $search['clinic']])->render()!!}</td>
                            </tr>
                            
                        </tfoot>
                      @endif
                    </table>
                  </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          
        </div>
       
      </div>
      <!-- /.row -->

    </section>

   
    <invoice-modal url="/medic/facturas"></invoice-modal>


  @include('medic/facturas/partials/status-hacienda-modal')

@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
 <script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ elixir('/js/facturas.min.js') }}"></script>
 <script src="{{ elixir('/js/modalRespHacienda.min.js') }}"></script>
@endsection
