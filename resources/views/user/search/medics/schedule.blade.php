@extends('layouts.app-patient')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
  <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="/js/plugins/hopscotch/css/hopscotch.min.css"> 
@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
  @include('layouts/partials/header-pages',['page'=>'Reserva tu cita'])


    <section class="content">
       @if(auth()->user()->patients->count())
         @if($office->active)
        <div class="row">
        <div class="col-md-3">
          
          <!-- /. box -->
        
          <div class="box box-solid box-create-appointment">
            <div class="box-header with-border">
              <h4 class="box-title">Tu cita</h4>
            </div>
            <div class="box-body">
              <!-- the events -->
              @if($medic->phone)
                 <p> Reserve su cita en la hora deseada</p>
                  
              @endif
              <div id="external-events">
                <div class="external-event bg-primary" data-patient="{{ (auth()->user()->patients->first()) ? auth()->user()->patients->first()->id : auth()->id() }}" data-doctor="{{ $medic->id }}" data-createdby="{{ auth()->id() }}" data-office="{{ $office->id }}">Cita</div>
              </div>
            
              <p class="text-muted well well-sm no-shadow text-center" style="margin-top: 10px;">
                Arrastra el elemento de arriba llamado cita dentro del calendario en la fecha y hora deseado!
              </p>
             
              
            </div>
            <!-- /.box-body -->
          </div>
          <p class="text-muted well well-sm no-shadow text-center" style="margin-top: 10px;">
                Puedes hacer click en una celda del calendario para crear la cita en la hora deseada!
              </p>
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-default box-calendar patient-calendar">
            <div class="box-header text-center">
             <h2 class="box-title"><b>{{ $office->name }}</b></h2> 
            </div>
            <div class="box-body no-padding">
              @include('layouts/partials/loading')
              <!-- THE CALENDAR -->
              <div id="calendar" data-slotDuration="{{ ($medic->settings) ? $medic->settings->slotDuration : '00:30:00' }}" data-minTime="{{ $medic->settings->minTime }}" data-maxTime="{{ $medic->settings->maxTime }}" data-appointmentsday="{{ auth()->user()->appointmentsToday() }}" data-freeDays="{{ $medic->settings->freeDays }}"></div>

          
              <!-- Modal -->
              <modal-schedule :patient="{{ auth()->user()->patients->first() }}" :patients="{{ auth()->user()->patients }}" :phone="{{ $medic->phone }}"></modal-schedule>

               <modal-reminder></modal-reminder>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

         @else
              <div class="callout callout-danger"><h4>Clinica Inactiva!</h4><p>Regresar al buscador y revisa otra opción. <a href="/medics/general/search" >Regresar</a></p></div>
         @endif
     @else
          <div class="callout callout-danger"><h4>Recuerda !</h4> <p>Necesitas tener al menos un paciente registrado para poder realizar citas en linea. <a href="/account/edit?tab=patients" class="btn btn-sm btn-success">Registre su paciente</a></p></div>
     @endif
    </section>

 
@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/select2/select2.full.min.js"></script>
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/jquery-ui.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ elixir('/js/schedule.min.js') }}"></script>
 <script src="/js/plugins/hopscotch/js/hopscotch.min.js"></script> 
<script>
  var tour = {
      id: "crear-cita",
      
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

 
    hopscotch.startTour(tour);

   
    
</script>
@endsection
