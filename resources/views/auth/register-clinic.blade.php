@extends('layouts.login')

@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
@endsection
@section('content')

<div class="register-box">
  <div class="register-logo">
    <a href="/"><b>{{ config('app.name', 'Laravel') }}</b></a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Registra una nueva cuenta como Administrador de Clinica</p>

    <form  role="form" method="POST" action="{{ url('/clinic/register') }}">
        {{ csrf_field() }}
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


    <a href="{{ url('/login') }}" class="text-center">Ya tengo una cuenta</a>
  </div>
  <!-- /.form-box -->
</div>

@endsection
@section('scripts')
<script src="/js/plugins/select2/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

  });
</script>
@endsection