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
    
        @include('layouts/partials/header-pages',['page'=>'Agenda'])
    
    <section class="content">
      
        <div class="row">
        <div class="col-md-3">
          
          
         
       
          <div class="box box-solid box-medics">
            <div class="box-header with-border">
              <h4 class="box-title">Médicos </h4>
              <div><small>(Haz click en un medico para ver su agenda de citas)</small></div>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-medics">
                @foreach($medics as $doctor)
                  <a href="/clinic/appointments?medic={{$doctor->id }}" class="medic-item">{{ $doctor->name }}</a>
                @endforeach
              </div>
            </div>
            <!-- /.box-body -->
          </div>
       
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
         
          @if($medic)
          <div class="box box-default box-calendar">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->

              <div id="calendar" data-slotDuration="{{ $medic->settings->slotDuration }}" data-minTime="{{ $medic->settings->minTime }}" data-maxTime="{{ $medic->settings->maxTime }}" data-freeDays="{{ $medic->settings->freeDays }}" data-medic="{{ $medic->id }}"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          @else
            <div class="box box-default box-calendar">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->

              Selecciona un Medico para ver su agenda de citas
            </div>
            <!-- /.box-body -->
          </div>
          @endif
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
     
    </section>
    @if(isset($p))
        <modal-clinic-appointments :patient="{{ $p }}"></modal-clinic-appointments>
      @else
         <modal-clinic-appointments :office="{{ $office->id }}"></modal-clinic-appointments>
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
@if($medic)
<script src="{{ elixir('/js/clinic.appointments.min.js') }}"></script>
@endif
@endsection
