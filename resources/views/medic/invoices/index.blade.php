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
        
        <div class="col-md-6">
         
           <!-- <form method="POST" action="{{ url('/medic/balance') }}" class="form-horizontal">
                       {{ csrf_field() }}
                       <button type="submit" class="btn btn-danger">Ejecutar cierre de ventas</button>
                  </form> -->
       
          <h2>Facturas</h2>
          <div class="box box-default box-calendar">
          <div class="box-header">
              <div class="pull-left">
                  <form action="/medic/invoices" method="GET">
                        <div class="input-group input-group-sm" style="width: 150px;">
                          
                            
                            <input type="text" name="q" class="date form-control" placeholder="Fecha..." value="{{ isset($searchDate) ? $searchDate : '' }}">
                            <div class="input-group-btn">

                              <button type="submit" class="btn btn-primary">Buscar</button>
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
                        <th>Clínica</th>
                        <th>Paciente</th>
                        <th>Total</th>
                        @if($medic->fe)
                        <th>Estado Hacienda</th>
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
                             {{ $invoice->clinic->name }}
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
                            <td>{{ title_case($invoice->status_fe) }}  @if($invoice->clave_fe) - <a href="#" data-toggle="modal" data-target="#modalRespHacienda" title="Comprobar estado de factura" data-invoice="{{ $invoice->id }}"><b>Comprobar estado</b></a> @endif</td>
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
        <div class="col-md-6">
         
           <!-- <form method="POST" action="{{ url('/medic/balance') }}" class="form-horizontal">
                       {{ csrf_field() }}
                       <button type="submit" class="btn btn-danger">Ejecutar cierre de ventas</button>
                  </form> -->
       
          <h2>Consultas No Facturadas</h2>
          <div class="box box-default box-calendar">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
                 <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th>#</th>
                        
                        <th>Paciente</th>
                        <th>Consulta</th>
                        <th>Fecha</th>
                        <th>De</th>
                        <th>a</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($noInvoices as $appointment)
                          <tr>
                            <td>{{ $appointment->id }}</td>
                      
                             <td>
                             {{ ($appointment->patient) ? $appointment->patient->first_name : 'Paciente Eliminado' }}
                            </td>
                           
                            <td data-title="Motivo">{{ $appointment->title }}</td>
                            <td data-title="Fecha">{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}</td>
                            <td data-title="De">{{ \Carbon\Carbon::parse($appointment->start)->format('h:i:s A') }}</td>
                            <td data-title="a">{{ \Carbon\Carbon::parse($appointment->end)->format('h:i:s A') }}</td>
                          </tr>
                      @endforeach
                      
                      </tbody>
                     
                      @if ($noInvoices)
                        <tfoot>
                            <tr>
                              <td  colspan="5" class="pagination-container">{!!$noInvoices->appends(['q' => $searchDate])->render()!!}</td>
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
        <!-- /.col -->
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
 <script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ elixir('/js/invoices.min.js') }}"></script>
 <script src="{{ elixir('/js/modalRespHacienda.min.js') }}"></script>
@endsection
