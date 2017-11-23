<div class="form-group">
    <label for="title" class="col-sm-2 control-label">Nombre</label>

    <div class="col-sm-10">
      <input type="text" class="form-control" name="title"  value="{{ isset($plan) ? $plan->title : old('title') }}" required >
       @if ($errors->has('title'))
          <span class="help-block">
              <strong>{{ $errors->first('title') }}</strong>
          </span>
      @endif
      </div>
  </div>
  <div class="form-group">
    <label for="cost" class="col-sm-2 control-label">Costo</label>

    <div class="col-sm-10">
    <div class="input-group">
        <span class="input-group-addon">$</span>
        <input type="text" class="form-control" name="cost"  value="{{ isset($plan) ? $plan->cost : old('cost') }}" required >
    </div>
     
       @if ($errors->has('cost'))
          <span class="help-block">
              <strong>{{ $errors->first('cost') }}</strong>
          </span>
      @endif
      </div>
  </div>
  <div class="form-group">
    <label for="quantity" class="col-sm-2 control-label">Meses</label>

    <div class="col-sm-10">
      <input type="number" class="form-control" name="quantity" min="1" value="{{ isset($plan) ? $plan->quantity : old('quantity') }}" required >
       @if ($errors->has('quantity'))
          <span class="help-block">
              <strong>{{ $errors->first('quantity') }}</strong>
          </span>
      @endif
      </div>
  </div>
  
  <div class="form-group">
    <label for="description" class="col-sm-2 control-label">Descripción</label>

    <div class="col-sm-10">
      <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{ isset($plan) ? $plan->description : old('description') }}</textarea>
     
       @if ($errors->has('description'))
          <span class="help-block">
              <strong>{{ $errors->first('description') }}</strong>
          </span>
      @endif
    </div>
  </div>
  
  

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-danger">Guardar</button>
      <a href="/admin/plans" class="btn btn-default">Regresar</a>
    </div>
  </div>
