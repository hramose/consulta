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
     @if($wizard)
        @include('layouts/partials/header-pages',['page'=>'Arma tu agenda'])
     @else
        @include('layouts/partials/header-pages',['page'=>'Calendario de citas'])
     @endif
    <?php /* $datetime = new DateTime('now', 'America/Costa Rica');
           $datetime_string = $datetime->format('c'); 
          echo json_encode($datetime_string);*/
   ?>
    <section class="content">
      
        <div class="row">
        <div class="col-md-3">
          <form method="POST" action="{{ url('/medic/schedules/copyto/'. Carbon\carbon::now()->weekOfMonth) }}" class="form-horizontal">
                   {{ csrf_field() }}
                  <button class="btn bg-purple" style="margin-bottom: 1rem;float:right;">Copiar Horario de semana anterior</button>
          </form>

          <!-- /. box -->
          @if($wizard)
             
                <div class="modal fade" id="setupSchedule" role="dialog" aria-labelledby="setupSchedule">
                  <div class="modal-dialog " role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                      
                      <h4 class="modal-title" id="setupScheduleLabel">Programando tu agenda</h4>
                      </div>
                      <div class="modal-body" data-modaldate data-slotduration="30">
                          <div class="callout callout-info">
                            <h4>Informacion importante!</h4>

                            <p>Programa tu agenda buscando los consultorios o clinicas donde trabajas y asignale una fecha y horas determinadas. </p>
                          </div>
                          <div class="content form-horizontal">
                              <div class="row">
                                  <div class="col-xs-12">
                                     <div class="form-group">
                                        <form method="POST" action="{{ url('/medic/schedules/copyto/'. Carbon\carbon::now()->weekOfMonth) }}" class="form-horizontal">
                                                 {{ csrf_field() }}
                                                <button class="btn bg-purple">Copiar Horario de semana anterior</button>

                                        </form>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                <div class="col-xs-12">
                                  <div class="form-group">
                                        <label for="selectSlotDuration" class="cffol-sm-7 control-label">Pacientes cada: </label>
                                        <div class="ffcol-sm-5">
                                           <select name="selectSlotDurationModal" id="selectSlotDurationModal" class="form-control">
                                             <option value="02:00:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "02:00:00") ? 'selected' : '' : '' }}>2 h</option>
                                             <option value="01:30:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "01:30:00") ? 'selected' : '' : '' }}>1:30 h</option>
                                             <option value="01:00:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "01:00:00") ? 'selected' : '' : '' }}>1 h</option>
                                             <option value="00:30:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "00:30:00") ? 'selected' : '' : '' }}>30 min</option>
                                             <option value="00:20:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "00:20:00") ? 'selected' : '' : '' }}>20 min</option>
                                             <option value="00:10:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "00:10:00") ? 'selected' : '' : '' }}>10 min</option>
                                          </select>
                                        </div>
                                  </div>
                                  
                                </div>
                                 
                            </div>
                             <div class="row">
                               <div class="col-xs-12 col-sm-8">
                                  <div class="form-group">
                                          <select name="search-offices" id="search-offices" class="search-offices form-control select2 " style="width: 100%;">
                                            <!-- <option value=""></option> -->
                                          </select>
                                          <ul class="search-list todo-list">
                                          
                                         </ul>
                                  </div>
                                      
                                    
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                  <div class="form-group">
                                           <a href="/medic/account/edit?tab=clinics" class="btn btn-default ">Crear Consultorio Nuevo</a>
                                  </div>
                                      
                                    
                                </div>
                              </div>
                              <div class="row">
                               <div class="col-xs-12 col-sm-4">
                                  <div class="form-group">
                                    <label>Fecha:</label>
                                    <div class="input-group date col-sm-10">
                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                     
                                      <input type="text" class="form-control pull-right"  name="date" id="datetimepicker1">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-xs-6 col-sm-3">
                                  <div class="form-group">
                                    <label>Hora de inicio:</label>

                                      <div class="input-group col-xs-9 col-sm-10">
                                        <input type="text" class="form-control " name="start" id="datetimepicker2" >

                                        <div class="input-group-addon">
                                          <i class="fa fa-clock-o"></i>
                                        </div>
                                      </div>
                                  </div>
                                </div>
                                 <div class="col-xs-6 col-sm-3">
                                    <div class="form-group">
                                       <label>Hora de fin:</label>

                                        <div class="input-group col-xs-9 col-sm-10">
                                          <input type="text" class="form-control " name="end" id="datetimepicker3">

                                          <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="form-group">
                                
                               <button type="button" class="btn btn-primary add-cita">Agregar a agenda</button>
                              </div>
                            
                          </div>
                          

                           
                         
                         
                           
                      </div>
                       <div class="modal-footer" >
                       
                       
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar Asistente</button>
                       
                      </div>
                    </div>
                  </div>
                </div>
                
                
              

            <!-- <wizard-schedule></wizard-schedule> -->
          
          <div class="form-horizontal">
            <div class="form-group">
            
              <label for="selectSlotDuration" class="col-sm-7 control-label">Pacientes cada: </label>
              <div class="col-sm-5">
                 <select name="selectSlotDuration" id="selectSlotDuration" class="form-control">
                   <option value="02:00:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "02:00:00") ? 'selected' : '' : '' }}>2 h</option>
                   <option value="01:30:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "01:30:00") ? 'selected' : '' : '' }}>1:30 h</option>
                   <option value="01:00:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "01:00:00") ? 'selected' : '' : '' }}>1 h</option>
                   <option value="00:30:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "00:30:00") ? 'selected' : '' : '' }}>30 min</option>
                   <option value="00:20:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "00:20:00") ? 'selected' : '' : '' }}>20 min</option>
                   <option value="00:10:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "00:15:00") ? 'selected' : '' : '' }}>10 min</option>
                </select>
              </div>
             
            </div>
          </div>

          @endif
          @if(!$wizard)
          <div class="box box-solid box-create-appointment">
            <div class="box-header with-border">
              <h3 class="box-title">Crear Cita</h3>
            </div>
            <div class="box-body">
             @if(isset($p))
              <appointment-create :patient="{{ $p }}"></appointment-create>
            @else
                <appointment-create></appointment-create>
            @endif
            
        
            </div>
          </div>
       
          <div class="box box-solid box-citas">
            <div class="box-header with-border">
              <h4 class="box-title">Citas </h4>
              <div><small>(Arrastra los elementos en la hora deseada dentro del calendario o haz click en una hora del calendario para crear una cita personalizada)</small></div>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
                
              </div>
            </div>
            <!-- /.box-body -->
          </div>
        @endif
        @if($wizard)
          <div class="box box-solid box-offices">
            <div class="box-header with-border">
              <h4 class="box-title">Agenda </h4>
              <div><small>(Arrastra los elementos en la hora deseada dentro del calendario o haz click en una hora del calendario para crear una cita personalizada)</small></div>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-offices">
                <!-- <div class="external-event bg-red">No disponible</div> -->
              </div>
            </div>
            <!-- /.box-body -->
          </div>
      @endif
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-default box-calendar">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->

              <div id="calendar" data-slotDuration="{{ auth()->user()->settings->slotDuration }}" data-minTime="{{ auth()->user()->settings->minTime }}" data-maxTime="{{ auth()->user()->settings->maxTime }}" data-freeDays="{{ auth()->user()->settings->freeDays }}" data-schedule="{{ $wizard }}"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
     
    </section>

  <!-- Modal -->

       @if(isset($p))
        <modal-appointments :patient="{{ $p }}"></modal-appointments>
      @else
         <modal-appointments></modal-appointments>
      @endif
            

@endsection
@section('scripts')
<!-- <script src="https://unpkg.com/vue-select@1.3.3"></script>cv -->
<script src="/js/plugins/select2/select2.full.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/jquery-ui.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/jquery.ui.touch-punch.min.js"></script>
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
 <script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 

<script src="{{ elixir('/js/appointments.min.js') }}"></script>

@endsection
