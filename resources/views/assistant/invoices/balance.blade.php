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
    
    
       @include('layouts/partials/header-pages',['page'=>'Facturación: Cierre del día'])

     
    
    <section class="content">
       
        <div class="row">
        
        <div class="col-md-12">
         
          <div class="box box-default box-calendar">
            <div class="box-header">
               <strong> Facturas</strong> 
            </div>
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->

                 <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th>Médico</th>
                        <th>Consultas Atendidas (facturadas)</th>
                        <th>Total Facturado</th>
                      
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($statisticsInvoices['medics'] as $medic)
                          <tr>
                                <td>{{ $medic->name }}</td>
                                    <td>{{ $medic->invoices->count() }}</td>
                                    <td>{{ money(totalInvoices($medic->invoices)) }}</td>
                                   
                          </tr>
                        
                      @endforeach
                      <tr>
                            <td><b>Totales</b></td>
                            <td>{{ $statisticsInvoices['totalAppointments'] }}</b></td>
                            <td><b>{{ money($statisticsInvoices['totalInvoices']) }}</b></td>
                            
                            
                        </tr>
                      </tbody>
                     
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
<script src="/js/bootstrap.min.js"></script>


@endsection
