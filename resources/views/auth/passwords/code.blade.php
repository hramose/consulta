@extends('layouts.login')

@section('content')

  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Cambio de contraseña</p>

     <form role="form" method="POST" action="{{ url('/user/password/reset') }}">
            {{ csrf_field() }}
            

      <div class="form-group has-feedback">
        
         <input id="phone" type="text" class="form-control" name="phone" value="{{ $phone or old('phone') }}" required autofocus placeholder="Teléfono">

            @if ($errors->has('phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
            @endif
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
         <input id="password" type="password" class="form-control" name="password" required placeholder="Nueva Contraseña">

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirmación de nueva contraseña">

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
       <div class="form-group has-feedback">
        
         <input id="code" type="text" class="form-control" name="code" value="{{ old('code') }}" required autofocus placeholder="Código enviado por teléfono o correo">

            @if ($errors->has('code'))
                <span class="help-block">
                    <strong>{{ $errors->first('code') }}</strong>
                </span>
            @endif
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
        <!--<div class="col-md-6 col-md-offset-4">-->
          <button type="submit" class="btn btn-primary btn-block btn-flat">Cambiar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
@endsection
