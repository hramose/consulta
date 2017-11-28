@extends('layouts.app-clinic')
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
                  <form action="/clinic/patients/{{ $patient->id }}/invoices" method="GET">
                        
                          
                  <div class="col-xs-12 col-sm-4">
                          <div class="form-group">
                            <input type="text" class="form-control"  name="date1"  id="datepicker1" value="{{ isset($searchDate['date1']) ? $searchDate['date1'] : '' }}">

                            
                          </div>
                      </div>
                       <div class="col-xs-12 col-sm-4" >
                          <div class="form-group">
                            <input type="text" class="form-control"  name="date2" id="datepicker2"  value="{{ isset($searchDate['date2']) ? $searchDate['date2'] : '' }}">

                           
                          </div>
                      </div>
                           

                            <button type="submit" class="btn btn-default">Buscar</button>
                           
                          
                          
                        
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
                            <td>{{ $invoice->consecutivo }}</td>
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
                            
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalInvoice" data-id="{{ $invoice->id }}" data-patient="{{ $invoice->patient_id }}">
                                  <i class="fa fa-eye"></i> Detalle 
                                </button>
                            </td>
                          </tr>
                      @endforeach
                      
                      </tbody>
                     
                      @if ($invoices)
                        <tfoot>
                            <tr>
                              <td  colspan="5" class="pagination-container">{!!$invoices->appends(['date1' => $searchDate['date1'], 'date2' => $searchDate['date2']])->render()!!}</td>
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

    @include('clinic/invoices/partials/modal')
  


@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script src="{{ elixir('/js/clinic.invoices.min.js') }}"></script>
 
@endsection
