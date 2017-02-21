@extends('layouts.app-patient')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
  <link rel="stylesheet" href="/js/plugins/bootstrap-sweetalert/sweetalert.css">
@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
  @include('layouts/partials/header-pages',['page'=>'Reserva tu cita'])


    <section class="content">
       @if(auth()->user()->patients->count())
        <div class="row">
        <div class="col-md-3">
          
          <!-- /. box -->
        
          <div class="box box-solid box-create-appointment">
            <div class="box-header with-border">
              <h4 class="box-title">Tu cita</h4>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
                <div class="external-event bg-primary" data-patient="{{ (auth()->user()->patients->first()) ? auth()->user()->patients->first()->id : auth()->id() }}" data-doctor="{{ $id }}" data-createdby="{{ auth()->id() }}">Cita</div>
              </div>
              <!-- <input name="medic_id" type="hidden" value="{{ $id }}"> -->
              <!-- <input name="created_by" type="hidden" value="{{ auth()->id() }}"> -->
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
          <div class="box box-primary box-calendar">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>

             <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-patient="{{ auth()->id() }}" data-doctor="{{ $id }}">Open modal for @mdo</button> -->
              
          
              <!-- Modal -->
              <modal-schedule :patient="{{ auth()->user()->patients->first() }}" :patients="{{ auth()->user()->patients }}"></modal-schedule>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
     @else
          <div class="callout callout-danger"><h4>Recuerda !</h4> <p>Necesitas tener al menos un paciente registrado para poder realizar citas en linea.</p></div>
     @endif
    </section>


@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/select2/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/jquery-ui.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
<script src="{{ elixir('/js/schedule.min.js') }}"></script>

@endsection
