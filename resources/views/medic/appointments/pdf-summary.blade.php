@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
<style>
	  
	  #tableHtml{
			display:none;
		}

</style>

@endsection
@section('content')
 <section class="content">
 		<form action="/medic/appointments/{{ $appointment->id }}/pdf" method="POST" id="form-generate-pdf">
		 		<input type="hidden" name="htmltopdf" value="" id="htmltopdf">
				<button type="submit" class="btn btn-primary btn-lg">Descargar PDF</button>
		 </form>
			<section class="invoice">
				<!-- title row -->
				<!-- info row -->
				<div class="row invoice-info" style="margin-right: -15px;margin-left: -15px;">
        <div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
          <div class="logo" style="background-position: center center;background-size: contain;background-repeat: no-repeat;height: 140px;">
            <img src="{{ getLogo($appointment->office) }}" alt="logo" style="height: 140px;">
          </div>  
        
  
          
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
          <h2>{{ $appointment->office->name }}</h2>
          <address>
          {{ $appointment->office->type }}<br>
          {{ $appointment->office->canton }}, {{ $appointment->office->province }}<br>
          {{ $appointment->office->address }}<br>
          <b>Tel:</b> {{ $appointment->office->phone }}<br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
          <div class="invoice-number">
            <h3>Nro. Consulta:</h3>
            <h4>{{$appointment->id }}</h4>
          </div>  
          <div class="invoice-date">
          <b>Fecha:</b> Fecha: {{ \Carbon\Carbon::now() }}
          </div>
          
         
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <hr>
      <div class="row invoice-patient" style="margin-right: -15px;margin-left: -15px;">
        <div class="col-xs-4 invoice-col invoice-left" style="text-align: left;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">     
            <b>Paciente:</b> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}. {{ trans('utils.gender.'.$appointment->patient->gender) }}.<br>
						 <b>Fecha Nacimiento:</b> {{ age($appointment->patient->birth_date) }}<br>
		        
						<b>Fecha Consulta:</b> {{ $appointment->date }}<br>
        </div>
        <div class="col-xs-4 invoice-col invoice-right" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
           
        </div>
        <div class="col-xs-4 invoice-col invoice-right" style="text-align: right;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
						<b>Médico:</b> {{ auth()->user()->name }}<br>
						<b>Código de Médico:</b> {{ auth()->user()->medic_code }}<br>
            @foreach( auth()->user()->specialities as $speciality)
              {{ $speciality->name }} 
            @endforeach
        </div>
      </div>
      <hr>
		
		    <!-- Table row -->
		    <div class="row">
		      <div class="col-xs-12">
		        <div class="print-summary-page">
			      	<summary-appointment :history="{{ $history }}" :medicines="{{ $appointment->patient->medicines }}" :notes="{{ $appointment->diseaseNotes }}" :exams="{{ $appointment->physicalExams }}" :diagnostics="{{ $appointment->diagnostics }}" :treatments="{{ $appointment->treatments }}" instructions="{{ $appointment->medical_instructions }}" showhistory="true" :labexams="{{ $appointment->labexams }}" :is-current="true">
			      		Historia Clínica del Paciente
			      	</summary-appointment>
		      	</div>
		      </div>
		    </div>
		    <!-- /.row -->

		   
		   
	      
		  </section>
		  <!-- /.content -->
		
    
 
</section>

<div id="html-pdf">
 			<!-- Main content -->
		  <table id="tableHtml">
					<tr>
							<td>
									
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							<div class="logo" style="background-position: center center;background-size: contain;background-repeat: no-repeat;height: 90px;">
								<img src="{{ getLogo($appointment->office) }}" alt="logo" style="height: 90px;">
							</div>  
						
			
							
						</div>
					
									
							
							</td>
							<td>
										
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							<h2>{{ $appointment->office->name }}</h2>
							<address>
							{{ $appointment->office->type }}<br>
							{{ $appointment->office->canton }}, {{ $appointment->office->province }}<br>
							{{ $appointment->office->address }}<br>
							<b>Tel:</b> {{ $appointment->office->phone }}<br>
							</address>
						</div>
							
							</td>
							<td>
									
							<div class="col-sm-4 invoice-col" style="text-align: center;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							<div class="invoice-number">
								<h3 style="margin-bottom: 0; margin-top: 0;">Nro. Consulta:</h3>
								<h4 style="margin-bottom: 0; margin-top: 0;">{{$appointment->id }}</h4>
							</div>  
							<div class="invoice-date">
							<b>Fecha:</b> Fecha: {{ \Carbon\Carbon::now() }}
							</div>
							
						
							
						</div>
									
							
							</td>
					</tr>
					<tr>
							<td colspan="3">
							<hr>
							</td>	
					
					</tr>
					<tr>
							<td>
									  
							<div class="col-xs-4 invoice-col invoice-left" style="text-align: left;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">     
							<b>Paciente:</b> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}. {{ trans('utils.gender.'.$appointment->patient->gender) }}.<br>
							<b>Fecha Nacimiento:</b> {{ age($appointment->patient->birth_date) }}<br>
							
							<b>Fecha Consulta:</b> {{ $appointment->date }}<br>
					</div>
								
							</td>
							<td>
							
							</td>
							<td>
								
							<div class="col-xs-4 invoice-col invoice-right" style="text-align: right;width: 33.33333333%;float: left;position: relative;min-height: 1px;padding-right: 15px;padding-left: 15px;">
							<b>Médico:</b> {{ auth()->user()->name }}<br>
							<b>Código de Médico:</b> {{ auth()->user()->medic_code }}<br>
							@foreach( auth()->user()->specialities as $speciality)
								{{ $speciality->name }} 
							@endforeach
					</div>
							</td>
					</tr>
					<tr>
							<td colspan="3">
							<hr>
							</td>	
					
					</tr>
					<tr>
							<td style="width: 48%;">

							

									<h3 class="box-title"><slot>Historia Clínica del Paciente</slot></h3>
								
									<div class="summary-dl">
									
											<h4><strong >Medicamentos Activos</strong></h4><br>
										
												@foreach($appointment->patient->medicines as $item)
													<span>{{ $item->name }}</span><br>
												@endforeach
									
									
										<h4><strong >Notas de padecimiento</strong></h4><br>
											
												@if($appointment->diseaseNotes->reason)
												<span><strong>Razón de la visita: </strong>{{ $appointment->diseaseNotes->reason }}</span><br>
												@endif
												@if($appointment->diseaseNotes->symptoms)
												<span><strong>Síntomas subjetivos: </strong>{{ $appointment->diseaseNotes->symptoms }}</span><br>
												@endif
												@if($appointment->diseaseNotes->phisical_review)
												<span><strong >Exploración Física: </strong>{{ $appointment->diseaseNotes->phisical_review }}</span><br>
												@endif
										
									
												<h4><strong >Examen Físico</strong></h4><br>
									
											@if($appointment->physicalExams->cardio)
											<span><strong>Cardiaco y Vascular: </strong>{{ $appointment->physicalExams->cardio }} </span><br>
											@endif
											@if($appointment->physicalExams->linfatico)
											<div><strong >Sistema Linfático: </strong>{{ $appointment->physicalExams->linfatico }} </span><br>
											@endif
											@if($appointment->physicalExams->osteoarticular)
											<span><strong>Osteoarticular: </strong>{{ $appointment->physicalExams->osteoarticular }}</span><br>
											@endif
											@if($appointment->physicalExams->psiquiatrico)
											<span><strong>Psiquiátrico y Psicológico: </strong>{{ $appointment->physicalExams->psiquiatrico }} </span><br>
											@endif
											@if($appointment->physicalExams->digestivo)
											<span><strong>Aparato Digestivo: </strong>{{ $appointment->physicalExams->digestivo }} </span><br>
											@endif
											@if($appointment->physicalExams->dermatologico)
											<span><strong>Dermatológico: </strong>{{ $appointment->physicalExams->dermatologico }} </span><br>
											@endif
											@if($appointment->physicalExams->otorrinolaringologico)
											<span><strong>Otorrinolaringológico: </strong>{{ $appointment->physicalExams->otorrinolaringologico }} </span><br>
											@endif
											@if($appointment->physicalExams->reproductor)
											<span><strong>Aparato Reproductor: </strong>{{ $appointment->physicalExams->reproductor }} </span><br>
											@endif
											@if($appointment->physicalExams->urinario)
											<span><strong>Aparato Urinario: </strong>{{ $appointment->physicalExams->urinario }} </span><br>
											@endif
											@if($appointment->physicalExams->neurologico)
											<span><strong>Neurológico: </strong>{{ $appointment->physicalExams->neurologico }}</span><br>
											@endif
											@if($appointment->physicalExams->pulmonar)
											<span><strong>Pulmonar o Respiratorio: </strong>{{ $appointment->physicalExams->pulmonar }}</span>
											@endif
											<h4><strong >Examenes Laboratorio</strong></h4><br>
											@foreach($appointment->labexams as $item)
													<span>{{ $item->name }}</span><br>
											@endforeach
											<h4><strong >Diagnóstico</strong></h4><br>
										
											@foreach($appointment->diagnostics as $item)
											<span>{{ $item->name }}</span><br>
											@endforeach
											
										
											<h4><strong >Tratamiento</strong><h4><br>
										
											@foreach($appointment->treatments as $item)
											
											<span><strong>{{ $item->name }}:</strong>
													{{ $item->comments }}</span><br>
											
											@endforeach
											@if($appointment->medical_instructions)
											<span><strong>Recomendaciones  Médicas: </strong>{{ $appointment->medical_instructions }} </strong>
											@endif
										
								</div>
									
							
				
										
							</td>
							<td style="width: 48%;">
							<div class="summary-dl">
										<h4></strong>Historial</strong></h4>
										
											@if($history->allergies)	
											<strong>Alergias: </strong><br>@foreach($history->allergies as $item)<span>- {{ $item->name }}</span><br>@endforeach
											@endif
											@if($history->pathologicals)
											<br>	
											<strong>Ant. Patológicos: </strong><br>@foreach($history->pathologicals as $item)<span>- {{ $item->name }} </span><br>@endforeach 
											@endif
											@if($history->nopathologicals)
											<br>	
											<strong>Ant. No Patológicos: </strong><br>@foreach($history->nopathologicals as $item)<span>- {{ $item->name }} </span><br>@endforeach 
											@endif
											@if($history->heredos)
											<br>	
											<strong>Ant. Heredofamiliares: </strong><br>@foreach($history->heredos as $item)<span>- {{ $item->name }} </span><br>@endforeach
											@endif
											@if($history->ginecos)
											<br>	
											<strong>Ant. Gineco-obstetricios: </strong><br>@foreach($history->ginecos as $item)<span>- {{ $item->name }} </span>@endforeach 
											@endif
											
									
										
										
									</div>
							</td>
					</tr>
				
			</table>
			</div>
     
 @endsection
 @section('scripts')
 <script>
 	 function generatePDF() {
            $('#htmltopdf').val($('#html-pdf').html())
						$('#form-generate-pdf').submit();
        }
        window.onload = generatePDF;
 </script>
 @endsection
