@extends('layouts.app-admin')

@section('content')
    

    <section class="content">
        <div class="col-xs-12 col-sm-6">
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
      </div>
      <div class="col-xs-12 col-sm-6">
         <div class="box box-info">
          <div class="box-header with-border">
            Cobro de citas atendidas 
          </div>
          <div class="box-body">
            
                <!-- <form action="/admin/configuration" method="POST">
                    <label>Mensualidad por Uso de Expediente Clínico</label>
                    <div class="input-group">
                       {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
                      <input type="text" name="amount_expedient" class="form-control" value="{{ getAmountPerExpedientUse() }}">
                          <span class="input-group-btn">
                            <button type="submit" class="btn btn-info btn-flat">Guardar</button>
                          </span>
                    </div>
                 </form> -->
                  <br>
                  
                  <form action="/admin/configuration" method="POST">
                    <label>Monto por cita atendida</label>
                    <div class="input-group ">
                    
                      {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
                       <input type="text" name="amount_attended" class="form-control" value="{{ getAmountPerAppointmentAttended() }}">
                          <span class="input-group-btn">
                            <button type="submit" class="btn btn-info btn-flat">Guardar</button>
                          </span>
                     
                    </div>
                </form>
          </div>
        </div>
      </div>

    </section>

@endsection
