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
    
    
 
    @include('layouts/partials/header-pages',['page'=>'Facturas del paciente: '. $patient->fullname ])
       
    <section class="content">
       
        <div class="row">
        
        <div class="col-md-12">
         
       
         <h2>Facturas</h2>
         
        
          <div class="box box-default box-calendar">
            <div class="box-header">
              <div class="pull-left">
                  <form action="/assistant/patients/{{ $patient->id }}/invoices" method="GET">
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
                        <th>Médico</th>
                        <th>Total</th>
                        
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($invoices as $invoice)
                          <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>
                             {{ $invoice->created_at }}
                            </td>
                             <td>
                             {{ $invoice->medic->name }}
                            </td>
                           
                            <td>{{ money($invoice->total) }}</span></td>
                            <!-- <td>
                                @if($invoice->status)
                                  <span class="label label-success">Facturada</span>
                                @endif
                            </td> -->
                            <td>
                            
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalInvoice" data-id="{{ $invoice->id }}" data-medic="{{ $invoice->medic_id }}">
                                  <i class="fa fa-eye"></i> Detalle 
                                </button>
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

    @include('assistant/invoices/partials/modal')
  


@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script src="{{ elixir('/js/assistant.invoices.min.js') }}"></script>
 
@endsection
