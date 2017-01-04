@extends('layouts.login')

@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
@endsection
@section('content')

<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><b>Consulta</b></a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Registra una nueva cuenta</p>

    <form  role="form" method="POST" action="{{ url('/register') }}">
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
        <select class="form-control select2" style="width: 100%;" name="speciality_id" placeholder="-- Selecciona Especialidad --">
            <option value="0">Especialidad</option>
            @foreach ($specialities as $speciality)
              <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
            @endforeach
          </select>
          <!--<input type="text" class="form-control" name="province" placeholder="Provincia" value="{{ old('province') ?: isset($user->office) ? $user->office->province : '' }}">-->
           @if ($errors->has('speciality_id'))
              <span class="help-block">
                  <strong>{{ $errors->first('speciality_id') }}</strong>
              </span>
          @endif
        
      </div>
      <div class="row">
        
        <!-- /.col -->
        <div class="col-xs-4">
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