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
      <form role="form" method="POST" action="{{ url('/password/email') }}">
            {{ csrf_field() }}

      <div class="form-group has-feedback">
        
         <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="Email">

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
    
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
        <!--<div class="col-md-6 col-md-offset-4">-->
          <button type="submit" class="btn btn-primary btn-block btn-flat">Enviar link de cambio de contraseña</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->

@endsection
