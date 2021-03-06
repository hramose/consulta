@extends('layouts.login')

@section('content')

<!-- <div class="login-logo">
    <a href="/"><img src="/img/logo.png" alt="{{ config('app.name', 'Laravel') }}"></a>
  </div> -->
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><b>Acceso para médicos y clínica</b></p>

     <form role="form" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}
      <div class="form-group has-feedback">
        
        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
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
   
    <!-- /.social-auth-links -->
     <p>Si aun no tiene cuenta puedes crear una presionando en <a href="{{ url('/medic/register') }}" class=" text-center " ><b>Crear cuenta nueva</b>   </a></p>
    <a href="{{ url('/password/reset') }}">Olvidaste tu contraseña?</a><br>
    
    

  </div>
  <!-- /.login-box-body -->

@endsection
