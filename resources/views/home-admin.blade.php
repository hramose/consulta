@extends('layouts.app-admin')

@section('content')
    

    <section class="content">
        
        <div class="box box-info">
  			<div class="box-header with-border">
  				Link para compartir
  			</div>
  			<div class="box-body">
  				<label>Url de Registro de un médico</label>
  				<div class="input-group">
	                <span class="input-group-addon">@</span>
	                <input type="text" class="form-control" placeholder="Url de Registro de un médico" readonly value="{{  url('medic/register') }}">
	              </div>
	              <br>
	              <label>Url de Registro de una clínica y su administrador</label>
	              <div class="input-group">
	                <span class="input-group-addon">@</span>
	                <input type="text" class="form-control" placeholder="Url de Registro de una clinica y su administrador" readonly value="{{  url('clinic/register') }}">
	              </div>
  			</div>
  		</div>

    </section>

@endsection
