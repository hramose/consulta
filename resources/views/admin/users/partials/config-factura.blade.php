<div class="form-group">
    <label for="nombre" class="col-sm-2 control-label">Nombre o Razón social del emisor*</label>

    <div class="col-sm-5">
      <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="{{ isset($configFactura) ? $configFactura->nombre  : (isset($user->name) ? $user->name : old('nombre')) }}" required >
       @if ($errors->has('nombre'))
          <span class="help-block">
              <strong>{{ $errors->first('nombre') }}</strong>
          </span>
      @endif
      </div>

      <div class="col-sm-5">
      <input type="text" class="form-control" name="nombre_comercial" placeholder="Nombre Comercial" value="{{ isset($configFactura) ? $configFactura->nombre_comercial : old('nombre_comercial') }}" >
       @if ($errors->has('nombre_comercial'))
          <span class="help-block">
              <strong>{{ $errors->first('nombre_comercial') }}</strong>
          </span>
      @endif
      </div>
  </div>

  <div class="form-group">
    <label for="tipo_identificacion" class="col-sm-2 control-label">Identificación</label>

    <div class="col-sm-3">
        <select class="form-control select2" style="width: 100%;" name="tipo_identificacion">
        <option value="01" {{ isset($configFactura) ? ($configFactura->tipo_identificacion == '01' ? 'selected' : '') : '' }}>Cédula Física</option>
        <option value="02" {{ isset($configFactura) ? ($configFactura->tipo_identificacion == '02' ? 'selected' : '') : '' }}>Cédula Jurídica</option>
        <option value="03" {{ isset($configFactura) ? ($configFactura->tipo_identificacion == '03' ? 'selected' : '') : '' }}>DIMEX</option>
        <option value="04" {{ isset($configFactura) ? ($configFactura->tipo_identificacion == '04' ? 'selected' : '') : '' }}>NITE</option>
      </select>
      
      </div>
      <div class="col-sm-7">
      <input type="text" class="form-control" name="identificacion" placeholder="Numero de identificación" value="{{ isset($configFactura) ? $configFactura->identificacion : (isset($user->ide) ? $user->ide : old('identificacion')) }}" required >
       @if ($errors->has('identificacion'))
          <span class="help-block">
              <strong>{{ $errors->first('identificacion') }}</strong>
          </span>
      @endif
      </div>
  </div>

  <div class="form-group">
    <label for="sucursal" class="col-sm-2 control-label">Sucursal</label>

    <div class="col-sm-5">
      <input type="number" class="form-control" name="sucursal" placeholder="Número de Sucursal" value="{{ isset($configFactura) ? $configFactura->sucursal : old('sucursal','1') }}" required min="1">
       @if ($errors->has('sucursal'))
          <span class="help-block">
              <strong>{{ $errors->first('sucursal') }}</strong>
          </span>
      @endif
      </div>

      <div class="col-sm-5">
      <input type="number" class="form-control" name="pos" placeholder="Número de caja" value="{{ isset($configFactura) ? $configFactura->pos : old('pos','1') }}" required min="1">
       @if ($errors->has('pos'))
          <span class="help-block">
              <strong>{{ $errors->first('pos') }}</strong>
          </span>
      @endif
      </div>
  </div>

  <div class="form-group">
         <label for="provincia" class="col-sm-2 control-label">Dirección</label>
        <div class="col-sm-3">
            <select class="form-control select2" style="width: 100%;" name="provincia" id="provincia"  required>
            <option value="" style="color: #c3c3c3">Provincia</option>
            <option value="1" {{ isset($configFactura) ? ($configFactura->provincia == '1' ? 'selected' : '') : '' }}>San Jose</option>
            <option value="2" {{ isset($configFactura) ? ($configFactura->provincia == '2' ? 'selected' : '') : '' }}>Alajuela</option>
            <option value="3" {{ isset($configFactura) ? ($configFactura->provincia == '3' ? 'selected' : '') : '' }}>Cartago</option>
            <option value="4" {{ isset($configFactura) ? ($configFactura->provincia == '4' ? 'selected' : '') : '' }}>Heredia</option>
            <option value="5" {{ isset($configFactura) ? ($configFactura->provincia == '5' ? 'selected' : '') : '' }}>Guanacaste</option>
            <option value="6" {{ isset($configFactura) ? ($configFactura->provincia == '6' ? 'selected' : '') : '' }}>Puntarenas</option>
            <option value="7" {{ isset($configFactura) ? ($configFactura->provincia == '7' ? 'selected' : '') : '' }}>Limon</option>
            </select>
            
            
            @if ($errors->has('provincia'))
                <span class="help-block">
                    <strong>{{ $errors->first('provincia') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-sm-3">
            <select class="form-control select2" style="width: 100%;" name="canton" id="canton" required>
            <option value="">Canton</option>
            
            
            </select>
            
            @if ($errors->has('canton'))
                <span class="help-block">
                    <strong>{{ $errors->first('canton') }}</strong>
                </span>
            @endif
        </div>
        <div class="col-sm-3">
            <select class="form-control select2" style="width: 100%;" name="distrito" id="distrito"  required>
            <option value="">Distrito</option>
            
            
            </select>
            
            @if ($errors->has('distrito'))
                <span class="help-block">
                    <strong>{{ $errors->first('distrito') }}</strong>
                </span>
            @endif
        </div>


    </div>

    <div class="form-group">
        <label for="codigo_pais_tel" class="col-sm-2 control-label">Teléfono</label>

        <div class="col-sm-2">
        <input type="text" class="form-control" name="codigo_pais_tel" placeholder="Codigo Pais" value="{{ isset($configFactura) ? $configFactura->codigo_pais_tel  : old('codigo_pais_tel','506') }}"  >
        @if ($errors->has('codigo_pais_tel'))
            <span class="help-block">
                <strong>{{ $errors->first('codigo_pais_tel') }}</strong>
            </span>
        @endif
        </div>

        <div class="col-sm-3">
        <input type="text" class="form-control" name="telefono" placeholder="Teléfono" value="{{ isset($configFactura) ? $configFactura->telefono : old('telefono') }}" >
        @if ($errors->has('telefono'))
            <span class="help-block">
                <strong>{{ $errors->first('telefono') }}</strong>
            </span>
        @endif
        </div>
         <div class="col-sm-2">
        <input type="text" class="form-control" name="codigo_pais_fax" placeholder="Codigo Pais" value="{{ isset($configFactura) ? $configFactura->codigo_pais_fax  : old('codigo_pais_fax','506') }}" >
        @if ($errors->has('codigo_pais_fax'))
            <span class="help-block">
                <strong>{{ $errors->first('codigo_pais_fax') }}</strong>
            </span>
        @endif
        </div>

        <div class="col-sm-3">
        <input type="text" class="form-control" name="fax" placeholder="Fax" value="{{ isset($configFactura) ? $configFactura->fax : old('fax') }}" >
        @if ($errors->has('fax'))
            <span class="help-block">
                <strong>{{ $errors->first('fax') }}</strong>
            </span>
        @endif
        </div>
    </div>

<div class="form-group">
    <label for="barrio" class="col-sm-2 control-label">Otras Señas</label>

      <div class="col-sm-10">
      <input type="text" class="form-control" name="otras_senas" placeholder="Otras Señas" value="{{ isset($configFactura) ? $configFactura->otras_senas : old('otras_senas') }}" required >
       @if ($errors->has('otras_senas'))
          <span class="help-block">
              <strong>{{ $errors->first('otras_senas') }}</strong>
          </span>
      @endif
      </div>
  </div>
   
   
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>

    <div class="col-sm-10">
      <input type="email" class="form-control" name="email" placeholder="Email" value="{{ isset($configFactura) ? $configFactura->email : (isset($user->email) ? $user->email : old('email')) }}" required>
       @if ($errors->has('email'))
          <span class="help-block">
              <strong>{{ $errors->first('email') }}</strong>
          </span>
      @endif
    </div>
  </div>

  <div class="form-group">
    <label for="consecutivo_inicio" class="col-sm-2 control-label">Consecutivos de inicio</label>

      <div class="col-sm-3">
       
            <label for="consecutivo_inicio" class="control-label">Facturas</label>
            <input type="number" class="form-control" name="consecutivo_inicio" placeholder="Consecutivo Facturas" value="{{ isset($configFactura) ? $configFactura->consecutivo_inicio : old('consecutivo_inicio','1') }}" required min="1">
            @if ($errors->has('consecutivo_inicio'))
                <span class="help-block">
                    <strong>{{ $errors->first('consecutivo_inicio') }}</strong>
                </span>
            @endif
        
      </div>
      <div class="col-sm-3">
      <label for="consecutivo_inicio_ND" class="control-label">Notas Débito</label>
      <input type="number" class="form-control" name="consecutivo_inicio_ND" placeholder="Consecutivo Notas de débito" value="{{ isset($configFactura) ? $configFactura->consecutivo_inicio_ND : old('consecutivo_inicio_ND','1') }}" required min="1">
       @if ($errors->has('consecutivo_inicio_ND'))
          <span class="help-block">
              <strong>{{ $errors->first('consecutivo_inicio_ND') }}</strong>
          </span>
      @endif
      </div>
      <div class="col-sm-3">
       <label for="consecutivo_inicio_NC" class="control-label">Notas Crédito</label>
      <input type="number" class="form-control" name="consecutivo_inicio_NC" placeholder="Consecutivo Notas de crédito" value="{{ isset($configFactura) ? $configFactura->consecutivo_inicio_NC : old('consecutivo_inicio_NC','1') }}" required min="1">
       @if ($errors->has('consecutivo_inicio_NC'))
          <span class="help-block">
              <strong>{{ $errors->first('consecutivo_inicio_NC') }}</strong>
          </span>
      @endif
      </div>
  </div>
  
  
<div class="form-group">
    <label for="atv_user" class="col-sm-2 control-label">ATV usuario </label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="atv_user" placeholder="ATV usuario" value="{{ isset($configFactura) ? $configFactura->atv_user : old('atv_user') }}" required>
        @if ($errors->has('atv_user'))
          <span class="help-block">
              <strong>{{ $errors->first('atv_user') }}</strong>
          </span>
      @endif
    </div>

</div>
<div class="form-group">
    <label for="atv_password" class="col-sm-2 control-label">ATV Contraseña </label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="atv_password" placeholder="ATV Contraseña" value="{{ isset($configFactura) ? $configFactura->atv_password : old('atv_password') }}" required>
        @if ($errors->has('atv_password'))
          <span class="help-block">
              <strong>{{ $errors->first('atv_password') }}</strong>
          </span>
      @endif
    </div>

</div>
<div class="form-group">
    <label for="certificado" class="col-sm-2 control-label">Certificado .p12 </label>


    <div class="col-sm-10">
        <input type="file" class="form-control" name="certificado" placeholder="Certificado ATV">
        
        @if(isset($configFactura) && existsCertFile($configFactura))
            <h4 class="label label-success">Certificado Instalado</h4>
        @else 
            <h4 class="label label-danger">Certificado No Instalado</h4>
        @endif
    </div>
</div>
<div class="form-group">
    <label for="pin_certificado" class="col-sm-2 control-label">PIN certificado </label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="pin_certificado" placeholder="PIN del certificado" value="{{ isset($configFactura) ? $configFactura->pin_certificado : old('pin_certificado') }}" required>
        @if ($errors->has('pin_certificado'))
          <span class="help-block">
              <strong>{{ $errors->first('pin_certificado') }}</strong>
          </span>
      @endif
    </div>

</div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success">{{ isset($buttonText) ? $buttonText : 'Guardar'}}</button>
       @if(isset($configFactura) && $configFactura && !isset($read))
        <button type="submit" class="btn btn-danger" form="form-delete-configfactura" formaction="{!! url('/admin/users/'.$user->id.'/configfactura') !!}">Eliminar configuración</button>
       @endif
    </div>
  </div>
