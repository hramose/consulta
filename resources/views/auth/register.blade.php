@extends('layouts.login')

@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/magnific-popup/magnific-popup.css">
@endsection
@section('content')

<div class="register-box">
  <!-- <div class="register-logo">
     <a href="/"><img src="/img/logo.png" alt="{{ config('app.name', 'Laravel') }}"></a>
  </div> -->

  <div class="register-box-body">
    <p class="login-box-msg">Registra una nueva cuenta como Médico</p>

    <form  role="form" method="POST" action="{{ url('/medic/register') }}">
        {{ csrf_field() }}
      <div class="form-group has-feedback">
        <input id="ide" type="text" class="form-control" name="ide" value="{{ old('ide') }}" required autofocus placeholder="Cédula">
         @if ($errors->has('ide'))
            <span class="help-block">
                <strong>{{ $errors->first('ide') }}</strong>
            </span>
        @endif
        <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="Nombre y Apellidos">
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

<p>Estimado Dr(a): GPS Médica Versión 1.0 le da la bienvenida y pone a su disposición nuestro Expediente Clínico Virtual, Agenda Clínica, Citas en Línea y Gestor para Clínicas.</p>  

<p>Es importante entender que para poder acceder a la plataforma se requiere comprobar la veracidad de su información, de ahí que usted va a requerir su <b>firma digital</b>. En caso de no tenerla, le recomendamos solicitarla llamando a las oficinas del Banco Nacional más cercano.</p>  

<p>GPS Médica cargará un monto de <b>$1</b> por cada cita que sea atraída a la plataforma y reservada por el paciente (o usuario general). En caso de no ser atendida por el médico, esta no será sujeto de cobro. Tampoco serán sujetos de cobro las citas reservadas directamente por el médico u asistente (secretaria) en la plataforma.</p>

<p>Si el médico desea hacer uso del Expediente Clínico deberá cancelar un monto fijo mensual de <b>$10</b>.</p>


 <p>Para más información, le invitamos consultar nuestros nuestros <a href="https://gpsmedica.com/terminos-y-condiciones/" target="_blank">Términos y Condiciones.</a></p>
 <p class="text-center"><a href="#" class="btn btn-success" id="btn-aceptar-terms">Aceptar Términos y Condiciones</a> <a href="#" class="btn btn-danger" id="btn-cancel-terms">Cancelar</a></p>

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
      if ($('#conditions-popup').length && !sessionStorage.getItem("terms") ) {
          $.magnificPopup.open({
            items: {
              src: '#conditions-popup' 
            },
            type: 'inline',
            modal:true
            });
         $('#btn-aceptar-terms').on('click', function(e){
            sessionStorage.setItem("terms",1);
            $.magnificPopup.close();
         });
         $('#btn-cancel-terms').on('click', function(e){
           sessionStorage.removeItem("terms");
            $.magnificPopup.close();
            window.location.href = "https://gpsmedica.com/";
         });
           
        }
    });


  });
</script>
@endsection