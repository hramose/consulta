@extends('layouts.app-assistant')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
  <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 

@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
    
    
    @if($medic)
            @include('layouts/partials/header-pages',['page'=>'Facturas del médico: '. $medic->name ])
          @else
            @include('layouts/partials/header-pages',['page'=>'Facturación'])
          @endif
    <section class="content">
       
        <div class="row">
        
        <div class="col-md-12">
         
        
         <h2>Facturas</h2>
         <div>
           
            <a href="/assistant/medics/{{ $medic->id }}/no-invoices" class="btn btn-info">Ver consultas no facturadas</a>
           
         </div>
          <div class="box box-default box-calendar">
            <div class="box-header">
              <div class="pull-left">
                  <form action="/assistant/medics/{{ $medic->id }}/invoices" method="GET">
                        <div class="input-group input-group-sm" style="width: 150px;">
                          
                            
                            <input type="text" name="q" class="date form-control" placeholder="Fecha..." value="{{ isset($searchDate) ? $searchDate : '' }}">
                            <div class="input-group-btn">

                              <button type="submit" class="btn btn-default">Buscar</button>
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
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Total</th>
                         @if($medic->fe)
                        <th>Tipo Doc</th>
                        <th>Num. Consecutivo</th>
                        <th>Estado Hacienda</th>
                        <th>Generar NC</th>
                        <th>Generar ND</th>
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
                             {{ $invoice->appointment->patient->first_name }}
                            </td>
                           
                            <td>{{ money($invoice->total) }}</span></td>
                            <!-- <td>
                                @if($invoice->status)
                                  <span class="label label-success">Facturada</span>
                                @endif
                            </td> -->
                             @if($medic->fe)
                              <td>
                                {{ trans('utils.tipo_documento.'.$invoice->tipo_documento) }}
                                
                              </td>
                              <td>
                                {{ $invoice->consecutivo_hacienda }}
                              </td>
                            <td>
                              @if($invoice->sent_to_hacienda)
                                <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Comprobar estado de factura" data-invoice="{{ $invoice->id }}"><span class="label label-{{ trans('utils.status_hacienda_color.'.$invoice->status_fe) }}">{{ title_case($invoice->status_fe) }}</span>   </a> 
                               @elseif($invoice->status)
                                  
                                 <send-to-hacienda :invoice-id="{{ $invoice->id }}" url="/assistant/invoices"></send-to-hacienda>
                              @endif
                            </td>
                            <td>
                              <a href="/assistant/invoices/{{ $invoice->id }}/notacredito">Generar Nota Crédito</a>
                              
                            </td>
                            <td>
                             <a href="/assistant/invoices/{{ $invoice->id }}/notadebito">Generar Nota Debito</a>
                            
                            </td>
                            <td>
                              @if($invoice->status)
                              <a href="/assistant/invoices/{{ $invoice->id }}/download/xml">XML</a>
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
                              <td  colspan="5" class="pagination-container">{!!$invoices->appends(['q' => $searchDate])->render()!!}</td>
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

    @include('medic/invoices/partials/modal')
  
 @if($medic->fe)
    @include('medic/invoices/partials/status-hacienda-modal')
 @endif

@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script src="{{ elixir('/js/assistant.invoices.min.js') }}"></script>
  <script src="{{ elixir('/js/modalRespHacienda.min.js') }}"></script>
@endsection
