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
          <div class="box box-solid box-create-appointment">
            <div class="box-header with-border">
              <h3 class="box-title">Crear Cita</h3>
            </div>
            <div class="box-body">
              
              <!-- /btn-group -->
              <div class="form-group">
                <select class="search-patients select2 form-control" style="width:100%;">
                   @if(isset($p))
                    <option value="{{ $p->id }}" selected="selected">{{ $p->first_name }}</option>
                  @else
                    <option value="" selected="selected"></option>
                  @endif
                </select>
                <ul class="search-list todo-list">
                  
                 </ul>
              </div>
               <div class="form-group">
                <input id="new-event" type="text" class="form-control" placeholder="Motivo de la cita">
                <!-- <input name="user_id" type="hidden" value="{{ auth()->id() }}"> -->
              </div>
              
              <div class="form-group">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Agregar</button>
                </div>
              <!-- /input-group -->
            </div>
          </div>

          <div class="box box-solid box-offices">
            <div class="box-header with-border">
              <h4 class="box-title">Agenda</h4>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
                <div class="external-event bg-red">No disponible</div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary box-calendar">
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
  <!-- Modal -->
              <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      
                      <h4 class="modal-title" id="myModalLabel">Crea cita</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="form-group">
                          <select class="modal-search-patients select2 form-control" style="width:100%;">
                            
                            @if(isset($p))
                              <option value="{{ $p->id }}" selected="selected">{{ $p->first_name }}</option>
                            @else
                              <option value="" selected="selected"></option>
                            @endif
                          </select>
                          <ul class="search-list todo-list">
                            
                           </ul>
                        </div>
                         <div class="form-group">
                          <input id="modal-new-event" type="text" class="form-control" placeholder="Motivo de la cita" data-modaldate>
                          <!-- <input name="modal-user_id" type="hidden" value="{{ auth()->id() }}"> -->
                          <!-- <input type="hidden" name="modal-date" value=""> -->
                        </div>
                        
                        <div class="form-group">
                            <button id="modal-add-new-event" type="button" class="btn btn-primary btn-flat">Agregar</button>
                          </div>
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>

@endsection
@section('scripts')
<script src="/js/plugins/select2/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/js/plugins/fullcalendar/jquery-ui.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/jquery.ui.touch-punch.min.js"></script>
<script src="{{ elixir('/js/appointments.min.js') }}"></script>

@endsection
