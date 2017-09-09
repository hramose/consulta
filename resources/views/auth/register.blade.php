@extends('layouts.login')

@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/magnific-popup/magnific-popup.css">
@endsection
@section('content')

<div class="register-box">
  <div class="register-logo">
     <a href="/"><img src="/img/logo.png" alt="{{ config('app.name', 'Laravel') }}"></a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Registra una nueva cuenta como Médico</p>

    <form  role="form" method="POST" action="{{ url('/medic/register') }}">
        {{ csrf_field() }}
      <div class="form-group has-feedback">
        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="Nombre">
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
      <div class="form-group has-feedback">
        <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus placeholder="Numero de teléfono para contacto">
         @if ($errors->has('phone'))
            <span class="help-block">
                <strong>{{ $errors->first('phone') }}</strong>
            </span>
        @endif
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <select class="form-control select2" style="width: 100%;" name="speciality[]" multiple >
            <!-- <option value="">Especialidad</option> -->
            @foreach ($specialities as $speciality)
              <option value="{{ $speciality->id }}" >{{ $speciality->name }}</option>
            @endforeach
          </select>
          <!--<input type="text" class="form-control" name="province" placeholder="Provincia" value="{{ old('province') ?: isset($user->office) ? $user->office->province : '' }}">-->
           @if ($errors->has('speciality'))
              <span class="help-block">
                  <strong>{{ $errors->first('speciality') }}</strong>
              </span>
          @endif
        
      </div>
      <div class="form-group has-feedback">
        <input id="medic_code" type="text" class="form-control" name="medic_code" value="{{ old('medic_code') }}" required autofocus placeholder="Código de Médico">
         @if ($errors->has('medic_code'))
            <span class="help-block">
                <strong>{{ $errors->first('medic_code') }}</strong>
            </span>
        @endif
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


    <a href="{{ url('/login') }}" class="text-center">Ya tengo una cuenta</a>
  </div>
  <!-- /.form-box -->
</div>
<div id="conditions-popup" class="conditions-popup white-popup mfp-hide mfp-with-anim">
  <h3 style="text-align:center">CONDICIONES DE AFILIACIÓN:</h3>

 <p>Estimado Dr. , con el objetivo de que usted conozca nuestra plataforma, ponemos a su disposición GPS MEDICA 1.0, sistema que representa una versión demo limitada, en la que usted podrá hacer uso de algunos elementos de nuestra herramienta de manera gratuita y por un periodo mínimo de   3 meses.

</p> 

 <p>Nótese que durante el uso de esta versión GPS MEDICA 1.0, no nos comprometemos a  realizar MERCADEO que pretenda atraerle pacientes o agendar citas en línea más sin embargo, dicha plataforma está habilitada para permitirlo.</p> 

 <p>Lo invitamos a leer y visitar nuestros <a href="https://gpsmedica.com/terminos-y-condiciones/" target="_blank">Términos y Condiciones.</a></p>
 <p class="text-center"><a href="#" class="btn btn-success" id="btn-aceptar-terms">Aceptar</a> <a href="#" class="btn btn-danger" id="btn-cancel-terms">Cancelar</a></p>

</div>
@endsection
@section('scripts')
<script src="/js/plugins/select2/select2.full.min.js"></script>
<script src="/js/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2({
      placeholder: "Tu Especialidad",
      allowClear: true
    });
    $(window).on('load', function() {
      if ($('#conditions-popup').length) {
          $.magnificPopup.open({
            items: {
              src: '#conditions-popup' 
            },
            type: 'inline',
            modal:true
            });
         $('#btn-aceptar-terms').on('click', function(e){
            $.magnificPopup.close();
         });
         $('#btn-cancel-terms').on('click', function(e){
            $.magnificPopup.close();
            window.location.href = "https://gpsmedica.com/";
         });
           
        }
    });


  });
</script>
@endsection