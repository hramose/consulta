@extends('layouts.app-assistant')
@section('css')
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
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
         
       
          <h2>Consultas No Facturadas</h2>
          <div class="box box-default box-calendar">
               <div class="box-header">
              <div class="pull-left">
                  <form action="/assistant/no-invoices" method="GET">
                        <div class="input-group input-group-sm" style="width: 150px;">
                          
                            
                            <input type="text" name="q" class="date form-control" placeholder="Fecha..." value="{{ isset($searchDate) ? $searchDate : '' }}">
                            <div class="input-group-btn">

                              <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                          
                          
                        </div>
                      </form>
              </div>
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

   

@endsection
@section('scripts')
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script>
    $('.date').datetimepicker({
      format:'YYYY-MM-DD',
      locale: 'es',
      
   });
</script>
@endsection
