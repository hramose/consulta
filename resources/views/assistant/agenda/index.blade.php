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
       @include('layouts/partials/header-pages',['page'=>'Agenda del médico '. $medic->name ])
    @else
       @include('layouts/partials/header-pages',['page'=>'Agenda'])
    @endif
     
    
    <section class="content">
       
        <div class="row">
        <div class="col-md-4">
          
          
         
       
          <div class="box box-solid box-medics">
            <div class="box-header with-border">
              <h4 class="box-title">Médicos </h4>
              <div><small>(Haz click en un medico para ver su agenda de citas)</small></div>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-medics">
                <ul class="medic-list medic-list-in-box">
                  @foreach($medics as $doctor)
                    <li class="item {{ (isset($medic) && $doctor->id == $medic->id) ? 'medic-list-selected': '' }}">
                      <div class="medic-img">
                      <!--/img/default-50x50.gif-->
                        <img src="{{ Storage::url('avatars/'.$doctor->id.'/avatar.jpg') }}" alt="Medic Image" width="50" height="50">
                      </div>
                      <div class="medic-info">
                        <a href="/assistant/appointments?medic={{$doctor->id }}{{ (request('page')) ? '&page='.request('page') : '' }}" class="medic-title">{{ $doctor->name }}
                          </a>
                         
                           
                            <a href="/assistant/appointments?medic={{ $doctor->id }}{{ (request('page')) ? '&page='.request('page') : '' }}" class="label  label-info pull-right">Ver Calendario</a>
                           
                         
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
         
          @if($medic)
          <div class="box box-default box-calendar">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->

              <div id="calendar" data-slotDuration="{{ $medic->settings->slotDuration }}" data-minTime="{{ $medic->settings->minTime }}" data-maxTime="{{ $medic->settings->maxTime }}" data-freeDays="{{ $medic->settings->freeDays }}" data-medic="{{ $medic->id }}" data-office="{{ $office->id }}"></div>
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

                    <p>Selecciona un Médico para ver su agenda completa</p>
                </div>
                <div class="table-responsive">

                  <table class="table table-bordered">
                  <thead>
                    
                    <tr>
                      @foreach($medics as $doc)
                      <th class="text-center">
                         <div >{{ $doc->name }}</div> 
                         <small><span class="label label-warning">{{ $doc->specialities->first()->name }}</span></small>
                      </th>
                      @endforeach
                     
                    </tr>
                   
                    
                  </thead>
                  <tbody>
                    <tr>
                     @foreach($medics as $doc)
                      <td class="calendar-medic-day"><div id="calendar-m{{$doc->id}}" data-slotDuration="{{ $doc->settings->slotDuration }}" data-minTime="{{ $doc->settings->minTime }}" data-maxTime="{{ $doc->settings->maxTime }}" data-freeDays="{{ $doc->settings->freeDays }}" data-medic="{{ $doc->id }}" data-office="{{ $office->id }}"></div></td>
                      @endforeach
                    </tr>
                   
                  </tbody>
                </table>
                  
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
    @if(isset($p))
        <modal-clinic-appointments :patient="{{ $p }}"></modal-clinic-appointments>
      @else
         <modal-clinic-appointments :office="{{ $office->id }}"></modal-clinic-appointments>
      @endif


<form method="post" id="form-active-inactive">
 {{ csrf_field() }}
</form>

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
@else
  <script src="{{ elixir('/js/clinic.dailyagenda.min.js') }}"></script>
@endif
@endsection
