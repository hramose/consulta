@extends('layouts.login')

<!-- Main Content -->
@section('content')
<!-- <div class="login-logo">
     <a href="/"><img src="/img/logo.png" alt="{{ config('app.name', 'Laravel') }}"></a>
  </div> -->
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Cambio de contraseña</p>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
      <form role="form" method="POST" action="{{ url('/user/password/phone') }}">
            {{ csrf_field() }}

      <div class="form-group has-feedback">
        
         <input id="phone" type="phone" class="form-control" name="phone" value="{{ old('phone') }}" required placeholder="Teléfono">

        @if ($errors->has('phone'))
            <span class="help-block">
                <strong>{{ $errors->first('phone') }}</strong>
            </span>
        @endif
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
    
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
        <!--<div class="col-md-6 col-md-offset-4">-->
          <button type="submit" class="btn btn-primary btn-block btn-flat">Enviar código de cambio de contraseña</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->

@endsection
