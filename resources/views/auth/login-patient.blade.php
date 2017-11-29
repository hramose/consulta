@extends('layouts.login')

@section('content')

<div class="login-logo">
    <a href="/"><img src="/img/logo.png" alt="{{ config('app.name', 'Laravel') }}"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Inicio de sesión</p>

     <form role="form" method="POST" action="{{ url('/user/login') }}">
        {{ csrf_field() }}
      <div class="form-group has-feedback">
        
        <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus placeholder="Teléfono">
        @if ($errors->has('phone'))
            <span class="help-block">
                <strong>{{ $errors->first('phone') }}</strong>
            </span>
        @endif
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
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
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember"> Recuerdame
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Iniciar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
   
    <div class="social-auth-links text-center">
      <p>- O -</p>
      <a href="{{ url('/auth/facebook') }}" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Inicia usando
        Facebook</a>
      <a href="{{ url('/auth/google') }}" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Inicia usando
        Google+</a>
    </div>
    <!-- /.social-auth-links -->
     <p>Si aun no tiene cuenta puedes crear una presionando en <a href="{{ url('/register') }}" class=" text-center " ><b>Crear cuenta nueva</b>   </a></p>
    <a href="{{ url('/user/password/reset') }}">Olvidaste tu contraseña?</a><br>
    
    

  </div>
  <!-- /.login-box-body -->

@endsection
