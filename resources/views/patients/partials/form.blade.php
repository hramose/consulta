<div class="form-group">
    <label for="office_name" class="col-sm-2 control-label">Nombre</label>

    <div class="col-sm-10">
      <input type="text" class="form-control" name="first_name" placeholder="Nombre" value="{{ old('first_name') ?: isset($patient) ? $patient->first_name : '' }}" required {{ isset($patient) ? 'disabled' : ''}}>
       @if ($errors->has('first_name'))
          <span class="help-block">
              <strong>{{ $errors->first('first_name') }}</strong>
          </span>
      @endif
      </div>
  </div>
  <div class="form-group">
    <label for="last_name" class="col-sm-2 control-label">Apellidos</label>

    <div class="col-sm-10">
      <input type="text" class="form-control" name="last_name" placeholder="Apellidos" value="{{ old('last_name') ?: isset($patient) ? $patient->last_name : '' }}" {{ isset($patient) ? 'disabled' : ''}}>
       @if ($errors->has('last_name'))
          <span class="help-block">
              <strong>{{ $errors->first('last_name') }}</strong>
          </span>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label for="birth_date" class="col-sm-2 control-label">Fecha de Nacimiento</label>

    <div class="col-sm-10">
      <input type="text" class="form-control" name="birth_date" placeholder="Fecha de Nacimiento" value="{{ old('birth_date') ?: isset($patient) ? $patient->birth_date : '' }}" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask {{ isset($patient) ? 'disabled' : ''}}>
       @if ($errors->has('birth_date'))
          <span class="help-block">
              <strong>{{ $errors->first('birth_date') }}</strong>
          </span>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label for="gender" class="col-sm-2 control-label">Sexo</label>

    <div class="col-sm-10">
      <select class="form-control select2" style="width: 100%;" name="gender" placeholder="-- Selecciona Genero --" required {{ isset($patient) ? 'disabled' : ''}}>
        <option value=""></option>
        <option value="m" {{ isset($patient) ? $patient->gender == 'm' ? 'selected' : '' : '' }}>Masculino</option>
        <option value="f" {{ isset($patient) ? $patient->gender == 'f' ? 'selected' : '' : '' }}>Femenino</option>
      </select>
      <!--<input type="text" class="form-control" name="province" placeholder="Provincia" value="{{ old('province') ?: isset($patient->office) ? $patient->office->province : '' }}">-->
       @if ($errors->has('gender'))
          <span class="help-block">
              <strong>{{ $errors->first('gender') }}</strong>
          </span>
      @endif
    </div>
  </div>
   <div class="form-group">
    <label for="phone" class="col-sm-2 control-label">Teléfono</label>

    <div class="col-sm-10">
      <input type="text" class="form-control" name="phone" placeholder="Celular" value="{{ old('phone') ?: isset($patient) ? $patient->phone : '' }}" >
       @if ($errors->has('phone'))
          <span class="help-block">
              <strong>{{ $errors->first('phone') }}</strong>
          </span>
      @endif
    </div>
  </div>
  
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>

    <div class="col-sm-10">
      <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') ?: isset($patient) ? $patient->email : '' }}" >
       @if ($errors->has('email'))
          <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label for="address" class="col-sm-2 control-label">Dirección</label>

    <div class="col-sm-10">
      <input type="text" class="form-control" name="address" placeholder="Dirección" value="{{ old('address') ?: isset($patient) ? $patient->address : '' }}" >
       @if ($errors->has('address'))
          <span class="help-block">
              <strong>{{ $errors->first('address') }}</strong>
          </span>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label for="province" class="col-sm-2 control-label">Provincia</label>

    <div class="col-sm-10">
      <select class="form-control select2" style="width: 100%;" name="province" placeholder="-- Selecciona provincia --" required>
        <option></option>
        <option {{ isset($patient) ? $patient->province == 'Guanacaste' ? 'selected' : '' : '' }}>Guanacaste</option>
        <option {{ isset($patient) ? $patient->province == 'San Jose' ? 'selected' : '' : '' }}>San Jose</option>
        <option {{ isset($patient) ? $patient->province == 'Heredia' ? 'selected' : '' : '' }}>Heredia</option>
        <option {{ isset($patient) ? $patient->province == 'Limon' ? 'selected' : '' : '' }}>Limon</option>
        <option {{ isset($patient) ? $patient->province == 'Cartago' ? 'selected' : '' : '' }}>Cartago</option>
        <option {{ isset($patient) ? $patient->province == 'Puntarenas' ? 'selected' : '' : '' }}>Puntarenas</option>
         <option {{ isset($patient) ? $patient->province == 'Alajuela' ? 'selected' : '' : '' }}>Alajuela</option>
      </select>
      <!--<input type="text" class="form-control" name="province" placeholder="Provincia" value="{{ old('province') ?: isset($user->office) ? $user->office->province : '' }}">-->
       @if ($errors->has('province'))
          <span class="help-block">
              <strong>{{ $errors->first('province') }}</strong>
          </span>
      @endif
    </div>
  </div>
  <div class="form-group">
    <label for="office_city" class="col-sm-2 control-label">Ciudad</label>

    <div class="col-sm-10">
      <input type="text" class="form-control" name="city" placeholder="Ciudad" value="{{ old('city') ?: isset($patient) ? $patient->city : '' }}" >
       @if ($errors->has('city'))
          <span class="help-block">
              <strong>{{ $errors->first('city') }}</strong>
          </span>
      @endif
    </div>
  </div>
 
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-danger">Guardar</button>
    </div>
  </div>