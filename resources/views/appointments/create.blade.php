@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
  @include('layouts/partials/header-pages',['page'=>'Arma tu agenda'])


    <section class="content">
      
        <div class="row">
        <div class="col-md-3">
          
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Crear Cita</h3>
            </div>
            <div class="box-body">
              
              <!-- /btn-group -->
              <div class="form-group">
                <select class="search-patients select2 form-control" style="width:100%;">
                  <option value="" selected="selected"></option>
                </select>
                <ul class="search-list todo-list">
                  
                 </ul>
              </div>
               <div class="form-group">
                <input id="new-event" type="text" class="form-control" placeholder="Motivo de la cita">
                <input name="user_id" type="hidden" value="{{ auth()->id() }}">
              </div>
              <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                <ul class="fc-color-picker" id="color-chooser">
                  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                </ul>
              </div>
              <div class="form-group">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Agregar</button>
                </div>
              <!-- /input-group -->
            </div>
          </div>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h4 class="box-title">Agenda</h4>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
                <div class="external-event bg-red" data-doctor="{{ auth()->id() }}">No disponible</div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
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
<script src="/js/plugins/select2/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/jquery-ui.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/appointments.min.js"></script>

@endsection
