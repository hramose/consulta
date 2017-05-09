@extends('layouts.app-patient')
@section('css')
 
@endsection
@section('content')
    
    <div id="infoBox" class="alert alert-success" ></div>
  @include('layouts/partials/header-pages',['page'=>'Encuesta'])


    <section class="content">
       <div class="row">
       	 <div class="col-md-12">
       	  	<form method="POST" action="{{ url('/polls/'.$poll->id) }}" class="form-horizontal">
		       	<div class="box">
		       	  	
		       	  	<div class="box-body">
		       	  		
				            {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
							<p>Cómo  considera el servicio de atención médica recibida?</p>
							<input type="radio" name="medical_care" value="5">Excelente
							<input type="radio" name="medical_care" value="4">Muy Bueno
							<input type="radio" name="medical_care" value="3">Regular
							<input type="radio" name="medical_care" value="2">Malo
							 @if ($errors->has('medical_care'))
						          <span class="help-block">
						              <strong>{{ $errors->first('medical_care') }}</strong>
						          </span>
						      @endif
							<p>La respuesta del paciente tras el tratamiento estuvo a la altura de sus expectativas?</p>
							<input type="radio" name="treatment" value="5">Excelente
							<input type="radio" name="treatment" value="4">Muy Bueno
							<input type="radio" name="treatment" value="3">Regular
							<input type="radio" name="treatment" value="2">Malo
							 @if ($errors->has('treatment'))
						          <span class="help-block">
						              <strong>{{ $errors->first('treatment') }}</strong>
						          </span>
						      @endif
							<p>Califique con estrellas su nivel de satisfacción tras la atención de la consulta</p>
							
							<input type="radio" name="satisfaction" value="5">Excelente
							<input type="radio" name="satisfaction" value="4">Muy Bueno
							<input type="radio" name="satisfaction" value="3">Regular
							<input type="radio" name="satisfaction" value="2">Malo
							@if ($errors->has('satisfaction'))
						          <span class="help-block">
						              <strong>{{ $errors->first('satisfaction') }}</strong>
						          </span>
						      @endif
					        
				        
		       	  	</div>
		       	  	<div class="box-footer">
		       	  	
					        	
					       <input type="submit" value="Enviar" class="btn btn-info">
				
		       	  	</div>
		       	</div>
       	 	</form>
       		
       	 </div>
       	 
       </div>
       
         
    </section>


@endsection
@section('scripts')


@endsection
