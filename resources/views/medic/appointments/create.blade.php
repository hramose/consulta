@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
  <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 
  <link rel="stylesheet" href="/js/plugins/tooltipster.bundle.min.css"> 
  <link rel="stylesheet" href="/js/plugins/hopscotch/css/hopscotch.min.css"> 
  <style>
    .tooltipster-sidetip.tooltipster-noir.tooltipster-gps .tooltipster-box {
      background: #605ca8;
      border: 3px solid #605ca8;
      border-radius: 6px;
      box-shadow: 5px 5px 2px 0 rgba(0,0,0,0.4);
    }

    .tooltipster-sidetip.tooltipster-noir.tooltipster-gps .tooltipster-content {
      color: white;
      padding: 8px;
    }
  </style>
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
        @if(!$wizard)
       <div class="row">
       <div class="col-md-12">
        <div class="panel">
          <div class="panel-body">
          @include('layouts/partials/buttons-agenda-clinic')
          
          </div>
         
        </div>
         
        </div>
       </div>
       @endif
        <div class="row">
        <div class="col-md-3">
        
              
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
                   <option value="00:10:00" {{ (auth()->user()->settings) ? (auth()->user()->settings->slotDuration == "00:10:00") ? 'selected' : '' : '' }}>10 min</option>
                </select>
              </div>
             
            </div>
          </div>

          @endif
          @if(!$wizard)
          <div class="box box-solid box-horario-appointment">
            <div class="box-header with-border bg-green">
             <h3 class="box-title">Horarios Programados</h3>  <!-- <small id="currentWeek">Actual</small> -->
             
            </div>
            <div class="box-body">
             
              <div id="miniCalendar" data-slotDuration="{{ auth()->user()->settings->slotDuration }}" data-minTime="{{ auth()->user()->settings->minTime }}" data-maxTime="{{ auth()->user()->settings->maxTime }}" data-freeDays="{{ auth()->user()->settings->freeDays }}"></div>
                 
            </div>
          </div>
       
            
           
          <!-- <div class="box box-solid box-create-appointment">
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
          </div> -->
          @if($wizard)
          <div class="box box-solid box-citas">
            <div class="box-header with-border">
              <h4 class="box-title">Citas </h4>
              
               <div><small>(Arrastra los elementos coloreados hacia la hora deseada para programar tu agenda y expande hasta el fin de la jornada o has click sobre la hora para crear una cita)</small></div>

             <!-- <div><small>(Arrastra los elementos en la hora deseada dentro del calendario o haz click en una hora del calendario para crear una cita personalizada)</small></div> -->
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
                
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          @endif
        @endif
        @if($wizard)
          <div class="box box-solid box-offices">
            <div class="box-header with-border">
              <h4 class="box-title">Agenda </h4>
               @if($wizard)
               <div><small>(Arrastra los elementos coloreados hacia la hora deseada para programar tu agenda y expande hasta el fin de la jornada o has click sobre la hora para crear una cita)</small></div>
              @else
              <div><small>(Arrastra los elementos en la hora deseada dentro del calendario o haz click en una hora del calendario para crear una cita personalizada)</small></div>
             
              @endif
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-offices">
                <!-- <div class="external-event bg-red">No disponible</div> -->
              </div>
            </div>
            <!-- /.box-body -->
          </div>

          <div class="box box-solid box-offices">
            <div class="box-header with-border">
              <h4 class="box-title">Copiar Horario de Agenda </h4>
              
            </div>
            <div class="box-body">
             
              <form method="POST" action="{{ url('/medic/schedules/copyto/'. Carbon\carbon::now()->weekOfMonth) }}" class="form-horizontal">
                     {{ csrf_field() }}
                    
                   <!--  <div class="form-group">
                        <label for="selectWeek1" class="col-sm-7 control-label">De la Semana: </label>
                        <div class="col-sm-12">
                           <select name="selectWeek1" id="selectWeek1" class="form-control">
                             @foreach($selectWeeks as $week)
                             <option value="{{ $week['value'] }}">{{ $week['name'] }}</option>
                             @endforeach
                             
                          </select>
                        </div>
                  </div> -->
                
                  <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label>De la Fecha:</label>
                        <div class="date col-sm-12">
                         
                         
                          <input type="text" class="form-control pull-right"  name="dateini1" id="datetimepickerini1">
                        </div>
                      </div>
                    </div>
                  <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label>Hasta Fecha:</label>
                        <div class="date col-sm-12">
                         
                         
                          <input type="text" class="form-control pull-right"  name="dateini2" id="datetimepickerini2">
                        </div>
                      </div>
                    </div>
                 <!--  <div class="form-group">
                        <label for="selectWeek2" class="col-sm-7 control-label">A la Semana: </label>
                        <div class="col-sm-12">
                           <select name="selectWeek2" id="selectWeek2" class="form-control">
                             @foreach($selectWeeks as $week)
                             <option value="{{ $week['value'] }}">{{ $week['name'] }}</option>
                             @endforeach
                             
                          </select>
                        </div>
                  </div> -->
                 
                 
                       <h3>copiar a:</h3>
                       <div class="col-xs-12 col-sm-6">
                          <div class="form-group">
                            <label>De la Fecha:</label>
                            <div class="date col-sm-12">
                              
                             
                              <input type="text" class="form-control "  name="datefin1" id="datetimepickerfin1">
                            </div>
                          </div>
                        </div>
                      <div class="col-xs-12 col-sm-6">
                          <div class="form-group">
                            <label>Hasta Fecha:</label>
                            <div class="date col-sm-12">
                              
                             
                              <input type="text" class="form-control "  name="datefin2" id="datetimepickerfin2">
                            </div>
                          </div>
                        </div>
                     
                  <div class="form-group">
                      <div class="col-sm-12">
                        <button class="btn bg-purple" >Copiar</button>
                      </div>
                  </div>

            </form>

            </div>
            <!-- /.box-body -->
          </div>

            
      @endif
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-default box-calendar medico-calendar">
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
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/jquery-ui.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/jquery.ui.touch-punch.min.js"></script>
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
 <script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
 <script src="/js/plugins/tooltipster.bundle.min.js"></script> 
  <script src="/js/plugins/hopscotch/js/hopscotch.min.js"></script> 
<script src="{{ elixir('/js/appointments.min.js') }}"></script>

<script>
@if(isset($create))
  var tour = {
      id: "hello-hopscotch",
      
      i18n: {
          nextBtn: "Siguiente",
          prevBtn: "Atras",
          doneBtn: "Listo"
        },
       
      steps: [
        {
          title: "Crear Cita",
          content: "Selecciona una hora en el calendario",
          target: "#calendar .fc-thu",
          placement: "top",
          
        }
        
      ],
      onEnd: function () {
       
       // localStorage.setItem("tour_viewed", 1)

      }

    };

    //if(!localStorage.getItem("tour_viewed"))
    //{
      hopscotch.startTour(tour);
   // }
   
    

@endif
 
  /*$('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    html:true
});*/
//$('body').find('.tooltip').tooltipster({ theme: 'tooltipster-noir'});
$('body').on('mouseenter', '.tooltip:not(.tooltipstered)', function(){
    $(this)
        .tooltipster({
          theme: ['tooltipster-noir','tooltipster-gps']
        })
        .tooltipster('open');
});
  //$('[data-toggle="tooltip"]').tooltip(); 
  // var referenceElement = $('.appointment-details');
  // var onPopper = $('.appointment-details');
  // var popper = new Popper(referenceElement, onPopper, {
  //   placement: 'top'
  // });
</script>

@endsection
