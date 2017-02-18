@extends('layouts.login')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
 
@endsection
@section('content')

  <div class="register-box register-box-patient">
  <div class="register-logo">
    <a href="/"><b>Consulta</b></a>
  </div>
   
  <div class="register-box-body">
    <div class="callout callout-info"><h4>Ya casi terminas!</h4> <p>Agrega los siguientes datos de paciente para poder reservar citas con los médicos.</p></div>
    <form method="POST" action="{{ url('/patients/register') }}" class="form-horizontal register-patient">
         {{ csrf_field() }}
         
         <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="first_name" placeholder="Nombre" value="{{ auth()->user()->name }}" required>
               @if ($errors->has('first_name'))
                  <span class="help-block">
                      <strong>{{ $errors->first('first_name') }}</strong>
                  </span>
              @endif
              </div>
          </div>
          <div class="form-group">

            <div class="col-sm-12">
              <input type="text" class="form-control" name="last_name" placeholder="Apellidos" value="{{ old('last_name') }}" required>
               @if ($errors->has('last_name'))
                  <span class="help-block">
                      <strong>{{ $errors->first('last_name') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="birth_date" placeholder="Fecha de Nacimiento" value="{{ old('birth_date') }}" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask required>
               @if ($errors->has('birth_date'))
                  <span class="help-block">
                      <strong>{{ $errors->first('birth_date') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
           
            <div class="col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="gender" placeholder="-- Selecciona Genero --" required>
                <option value=""></option>
                <option value="m">Masculino</option>
                <option value="f">Femenino</option>
              </select>
              
               @if ($errors->has('gender'))
                  <span class="help-block">
                      <strong>{{ $errors->first('gender') }}</strong>
                  </span>
              @endif
            </div>
          </div>
           <div class="form-group">

            <div class="col-sm-12">
              <input type="text" class="form-control" name="phone" placeholder="Teléfono (celular)" value="{{ old('phone') }}" required>
               @if ($errors->has('phone'))
                  <span class="help-block">
                      <strong>{{ $errors->first('phone') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          
          <div class="form-group">
           
            <div class="col-sm-12">
              <input type="email" class="form-control" name="email" placeholder="Email" value="{{ auth()->user()->email }}" required>
               @if ($errors->has('email'))
                  <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="address" placeholder="Dirección" value="{{ old('address') }}" >
               @if ($errors->has('address'))
                  <span class="help-block">
                      <strong>{{ $errors->first('address') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
         
            <div class="col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="province" placeholder="-- Selecciona provincia --" required>
                <option></option>
                <option>Guanacaste</option>
                <option>San Jose</option>
                <option>Heredia</option>
                <option>Limon</option>
                <option>Cartago</option>
                <option>Puntarenas</option>
                 <option>Alajuela</option>
              </select>
              
               @if ($errors->has('province'))
                  <span class="help-block">
                      <strong>{{ $errors->first('province') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="city" placeholder="Ciudad" value="{{ old('city') }}" >
               @if ($errors->has('city'))
                  <span class="help-block">
                      <strong>{{ $errors->first('city') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="conditions" placeholder="Padecimientos" data-role="tagsinput" value="{{ old('coditions') }}" >
               @if ($errors->has('conditions'))
                  <span class="help-block">
                      <strong>{{ $errors->first('conditions') }}</strong>
                  </span>
              @endif
            </div>
          </div>
         
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
              <button type="submit" class="btn btn-danger">Guardar</button>
            </div>
          </div>
    </form>


  </div>
  <!-- /.form-box -->
</div>

		
@endsection
@section('scripts')
<script src="/js/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/js/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/js/plugins/select2/select2.full.min.js"></script>
<script src="/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script src="/js/patient_register.min.js"></script>
<!-- <script>
  $(function () {
    $("select[name='province']").select2({
      placeholder: "Selecciona Provincia",
      allowClear: true
    });
    $("select[name='gender']").select2({
      placeholder: "Selecciona Genero",
      allowClear: true
    });
    $("[data-mask]").inputmask();
  });
</script> -->
@endsection
