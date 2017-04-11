@extends('layouts.app-clinic-temp')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
  <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 

@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
    
        @include('layouts/partials/header-pages',['page'=>'Medicos Registrados en esta clinica'])
    
    <section class="content">
      
        <div class="row">
        <div class="col-md-4">
          
          
         
       
          <div class="box box-solid box-medics">
            <div class="box-header with-border">
              <h4 class="box-title">Médicos </h4>
              <div><small>(Registra un perfil de clinica para administrar los medicos)</small></div>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-medics">
                  <ul class="medic-list medic-list-in-box">
                    @foreach($medics as $doctor)
                      <li class="item">
                        <div class="medic-img">
                        <!--/img/default-50x50.gif-->
                          <img src="{{ Storage::url('avatars/'.$doctor->id.'/avatar.jpg') }}" alt="Medic Image" width="50" height="50">
                        </div>
                        <div class="medic-info">
                          <a href="javascript:void(0)" class="medic-title">{{ $doctor->name }}
                            <span class="label {{ ($doctor->verifyOffice($office->id)) ? 'label-info' : 'label-danger'}} pull-right">{{ ($doctor->verifyOffice($office->id)) ? 'Registrado' : 'Pendiente' }}</span></a>
                              <span class="medic-description">
                                E: {{ $doctor->email }}, T: {{ $doctor->phone }}
                              </span>
                        </div>
                      </li>
                     
                    @endforeach

                  </ul>
              
              </div>
            </div>
            <!-- /.box-body -->
          </div>
       
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-8">
         
         
            <div class="box box-default box-calendar">
            <div class="box-body no-padding">
              <div class="register-box">
                <div class="register-logo">
                  <a href="/"><b> {{ config('app.name', 'Laravel') }}</b></a>
                </div>

                <div class="register-box-body">
                  <p class="login-box-msg">Registra una nueva cuenta como Clinica para modificar datos</p>

                  <form  role="form" method="POST" action="{{ url('/clinic/register') }}">
                      {{ csrf_field() }}
                      <input id="office" type="hidden" name="office" value="{{ $office->id }}">
                    <div class="form-group has-feedback">
                      <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="Nombre del administrador de la clinica">
                       @if ($errors->has('name'))
                          <span class="help-block">
                              <strong>{{ $errors->first('name') }}</strong>
                          </span>
                      @endif
                      <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                      <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Email">

                      @if ($errors->has('email'))
                          <span class="help-block">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                      @endif
                      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                      <input id="password" type="password" class="form-control" name="password" required placeholder="Contraseña">

                      @if ($errors->has('password'))
                          <span class="help-block">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                      <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirmación de contraseña">
                      <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    </div>
                    
                    
                    <div class="row">
                      
                      <!-- /.col -->
                      <div class="col-xs-12 col-sm-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Registrar</button>
                      </div>
                      <!-- /.col -->
                    </div>
                  </form>
                  

                  
                </div>
                <!-- /.form-box -->
              </div>

              
             



            </div>
            <!-- /.box-body -->
          </div>
         
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
