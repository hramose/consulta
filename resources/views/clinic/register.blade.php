@extends('layouts.login')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
 
@endsection
@section('content')

  <div class="register-box register-box-patient">
  <div class="register-logo">
    <a href="/"><b>{{ config('app.name', 'Laravel') }}</b></a>
  </div>
   
  <div class="register-box-body">
    <div class="callout callout-info"><h4>Ya casi terminas!</h4> <p>Agrega los siguientes datos de la clínica para finalizar el rergistro.</p></div>
    <form method="POST" action="{{ url('/clinic/register/office') }}" class="form-horizontal register-patient">
         {{ csrf_field() }}
         
         <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="name" placeholder="Nombre de la clínica u hospital" value="" required>
               @if ($errors->has('name'))
                  <span class="help-block">
                      <strong>{{ $errors->first('name') }}</strong>
                  </span>
              @endif
              </div>
          </div>
          <div class="form-group">

            <div class="col-sm-12">
              <input type="text" class="form-control" name="address" placeholder="Dirección" value="{{ old('address') }}" required>
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
              <select class="form-control select2" style="width: 100%;" name="canton" placeholder="-- Selecciona canton --" required>
                <option></option>
                <option>Liberia</option>
               
              </select>
              
               @if ($errors->has('canton'))
                  <span class="help-block">
                      <strong>{{ $errors->first('canton') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
         
            <div class="col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="district" placeholder="-- Selecciona district --" required>
                <option></option>
                <option>Liberia</option>
               
              </select>
              
               @if ($errors->has('district'))
                  <span class="help-block">
                      <strong>{{ $errors->first('district') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="phone" placeholder="Teléfono" value="{{ old('phone') }}" required>
               @if ($errors->has('phone'))
                  <span class="help-block">
                      <strong>{{ $errors->first('phone') }}</strong>
                  </span>
              @endif
            </div>
          </div>
         
         
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
              <button type="submit" class="btn btn-danger">Finalizar</button>
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
