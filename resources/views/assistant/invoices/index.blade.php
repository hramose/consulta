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
       @include('layouts/partials/header-pages',['page'=>'Facturas del medico '. $medic->name ])
    @else
       @include('layouts/partials/header-pages',['page'=>'Facturación'])
    @endif
     
    
    <section class="content">
       
        <div class="row">
        <div class="col-md-4">
          
          
       
                  <a href="/assistant/invoices/balance" class="btn btn-danger">Ver Cierre del Dia
                          </a>
       
          <div class="box box-solid box-medics">
            <div class="box-header with-border">
              <h4 class="box-title">Médicos </h4>
              <div><small>(Haz click en un medico para ver sus facturas)</small></div>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-medics">
                <ul class="medic-list medic-list-in-box">
                  @foreach($medics as $doctor)
                    <li class="item {{ (isset($medic) && $doctor->id == $medic->id) ? 'medic-list-selected': '' }}">
                      <div class="medic-img">
                      <!--/img/default-50x50.gif-->
                        
                         <img src="{{ getAvatar($doctor) }}" alt="Medic Image" width="50" height="50">
                      </div>
                      <div class="medic-info">
                        <a href="/assistant/medics/{{$doctor->id }}/invoices" class="medic-title">{{ $doctor->name }}
                          </a>
                         
                           
                            <a href="/assistant/medics/{{$doctor->id }}/invoices" class="label  label-info pull-right">Ver Facturas</a>
                           
                         
                            <span class="medic-description">
                              E: {{ $doctor->email }}, T: {{ $doctor->phone }}
                            </span>
                      </div>
                    </li>
                   
                  @endforeach
                  
                </ul>
                @if ($medics)
                        <div  class="pagination-container">{!!$medics->render()!!}</div>
                    @endif
               
              </div>
            </div>
            <!-- /.box-body -->
          </div>
       
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-8">
         
          @if($invoices)
          <div class="box box-default box-calendar">
            <div class="box-header">
               <strong> Ultimas Facturas</strong> 
            </div>
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->

                 <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>Médico</th>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($invoices as $invoice)
                          <tr>
                            <td>{{ $invoice->id }}</td>
                             <td>{{ $invoice->medic->name }}</td>
                             <td>{{ $invoice->appointment->patient->first_name }}</td>
                            <td>
                             {{ $invoice->created_at }}
                            </td>
                           
                            <td>{{ money($invoice->total) }}</span></td>
                            <!-- <td>
                                @if($invoice->status)
                                  <span class="label label-success">Facturada</span>
                                @endif
                            </td> -->
                            <td>
                            
                                 <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalInvoice" data-id="{{ $invoice->id }}" data-medic="{{ $invoice->user_id }}">
                                  <i class="fa fa-eye"></i> Facturar
                                </button>
                            </td>
                          </tr>
                      @endforeach
                      
                      </tbody>
                     
                    </table>
                  </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          @else
            <div class="box box-default box-calendar">
            <div class="box-body ">
              <!-- THE CALENDAR -->
               <div class="callout callout-info">
                    <h4>Información importante!</h4>

                    <p>Selecciona un Médico para ver sus facturas</p>
                </div>
                
                

        
            </div>
            <!-- /.box-body -->
          </div>
          @endif
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>

     @include('assistant/invoices/partials/modal')
  


@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script src="{{ elixir('/js/assistant.invoices.min.js') }}"></script>

@endsection
