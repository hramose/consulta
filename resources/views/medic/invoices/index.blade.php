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
       
          <h2>Facturas</h2>
          <div>
           
            <a href="/medic/no-invoices" class="btn btn-info">Ver consultas no facturadas</a>
            @if(auth()->user()->hasRole('medico'))
            <a href="/medic/invoices/create" class="btn btn-success">Crear Factura</a>
            @endif
         </div>
          <div class="box box-default box-calendar">
          <div class="box-header">
              <div class="">
                  <form action="/medic/invoices" method="GET" class="form-horizontal">
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
            <div class="pull-right"><span class="label label-success label-lg">Total: {{ money($totalInvoicesAmount) }}</span>    </div> 
            </div>
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
                 <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        
                        <th>Consecutivo</th>
                        <th>Fecha</th>
                        <th>Clínica</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Tipo Doc</th>
                        <th>Generar NC</th>
                        <th>Generar ND</th>
                        @if(auth()->user()->fe)
                          <th>Consecutivo Hacienda</th>
                          <th>Estado Hacienda</th>
                          <th>Ver XML</th>
                        @endif
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($invoices as $invoice)
                          <tr>
                            
                            <td>{{ $invoice->consecutivo }}</td>
                        
                            <td>
                             {{ $invoice->created_at }}
                            </td>
                            <td>
                              @if($invoice->clinic)
                                {{ $invoice->clinic->name }}
                             @else 
                              --
                             @endif
                           
                            </td>
                            <td>
                             {{ $invoice->client_name }}
                            
                            </td>
                           
                            <td>{{ money($invoice->total) }}</span></td>
                            <td>
                               
                                <span class="label label-{{ trans('utils.tipo_documento_color.'.$invoice->tipo_documento) }}"> {{ trans('utils.tipo_documento.'.$invoice->tipo_documento) }}</span>
                                
                              </td>
                            <td>
                                 @if($invoice->status)
                                  <a href="/medic/invoices/{{ $invoice->id }}/notacredito">Generar Nota Crédito</a>
                                   @else 
                                      --
                                  @endif
                              </td>
                              <td>
                                 @if($invoice->status)
                                  <a href="/medic/invoices/{{ $invoice->id }}/notadebito">Generar Nota Debito</a>
                                 @else 
                                      --
                                  @endif
                              </td>
                               @if(auth()->user()->fe)
                                  <td>
                                    @if($invoice->fe)
                                    {{ $invoice->consecutivo_hacienda }}
                                    @else 
                                      --
                                    @endif
                                  </td>
                                  <td>
                                  @if($invoice->fe)
                                      @if($invoice->sent_to_hacienda)
                                        @if($invoice->status_fe)
                                          <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Click para comprobar estado de factura" data-invoice="{{ $invoice->id }}"><span class="label label-{{ trans('utils.status_hacienda_color.'.$invoice->status_fe) }}">{{ title_case($invoice->status_fe) }}</span>   </a>
                                        @else
                                          <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Click para comprobar estado de factura" data-invoice="{{ $invoice->id }}"><span class="label label-warning">Comprobar</span>   </a>
                                        @endif
                                      @elseif($invoice->status)
                                          
                                        <send-to-hacienda :invoice-id="{{ $invoice->id }}"></send-to-hacienda>
                                      @else 
                                         --
                                      @endif
                                    @else 
                                      --
                                  @endif
                                
                                </td>
                                <td>
                                  @if($invoice->status && $invoice->fe)
                                  <a href="/medic/invoices/{{ $invoice->id }}/download/xml">XML</a>
                                  @else 
                                      --
                                  @endif
                                </td>
                               
                           @endif
                            <td>
                              @if($invoice->status)
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalInvoice" data-id="{{ $invoice->id }}" data-medic="{{ $medic->id }}">
                                  <i class="fa fa-eye"></i> Detalle  
                                </button>
                                @else
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalInvoice" data-id="{{ $invoice->id }}" data-medic="{{ $medic->id }}">
                                  <i class="fa fa-eye"></i> Facturar
                                </button>
                                @endif
                            </td>
                          </tr>
                      @endforeach
                      
                      </tbody>
                     
                      @if ($invoices)
                        <tfoot>
                            <tr>
                              <td  colspan="5" class="pagination-container">{!!$invoices->appends(['q' => $search['q'], 'date' => $search['date'], 'clinic' => $search['clinic']])->render()!!}</td>
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

   <invoice-modal></invoice-modal>
   
   @if(auth()->user()->fe)
    
    @include('medic/invoices/partials/status-hacienda-modal')
    
   @endif

@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ elixir('/js/invoices.min.js') }}"></script>
<script src="{{ elixir('/js/modalRespHacienda.min.js') }}"></script>
@endsection
