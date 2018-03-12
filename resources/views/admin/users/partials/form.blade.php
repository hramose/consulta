<div class="form-group">
    <label for="office_name" class="col-sm-2 control-label">Nombre</label>

    <div class="col-sm-10">
      <input type="text" class="form-control" name="name" placeholder="Nombre" value="{{ isset($user) ? $user->name : old('name') }}" required >
       @if ($errors->has('name'))
          <span class="help-block">
              <strong>{{ $errors->first('name') }}</strong>
          </span>
      @endif
      </div>
  </div>
  
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>

    <div class="col-sm-10">
      <input type="email" class="form-control" name="email" placeholder="Email" value="{{ isset($user) ? $user->email : old('email') }}" >
       @if ($errors->has('email'))
          <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
      @endif
    </div>
  </div>
  
  
<div class="form-group">
    <label for="password" class="col-sm-2 control-label">Contraseña: </label>
    <div class="col-sm-10">
        <input type="password" class="form-control" name="password" placeholder="Contraseña">
        @if ($errors->has('password'))
          <span class="help-block">
              <strong>{{ $errors->first('password') }}</strong>
          </span>
      @endif
    </div>

</div>

 <div class="form-group">
    <label for="fe" class="col-sm-2 control-label">Factura eléctronica</label>

    <div class="col-sm-10">
      <select class="form-control select2" style="width: 100%;" name="fe" required>
        <option value="0" {{ isset($user) ? $user->fe == '0' ? 'selected' : '' : '' }}>No</option>
        <option value="1" {{ isset($user) ? $user->fe == '1' ? 'selected' : '' : '' }}>Si</option>
      </select>
     
       @if ($errors->has('fe'))
          <span class="help-block">
              <strong>{{ $errors->first('fe') }}</strong>
          </span>
      @endif
    </div>
  </div>
  
 @if(!isset($read))
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-danger">Guardar</button>
    </div>
  </div>
  @endif