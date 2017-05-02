<div class="row">
	<div class="col-sm-6 col-xs-12">
		<h2>Consultas programadas</h2>
		@forelse($scheduledAppointments as $appointment)
			
				<a class="info-box cita-item" href="{{ (isset($fromPatient) || auth()->id() != $appointment->user->id) ? '#': '/medic/appointments/'.$appointment->id.'/edit'}}" style="text-align: left;">
		            <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>

		            <div class="info-box-content">
		              <span class="info-box-text">{{ $appointment->title }} con <small>Dr. {{ $appointment->user->name }}</small></span>
		              <span class="info-box-number">{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}</span>
		              <span class="info-box-text"><small>{{ \Carbon\Carbon::parse($appointment->start)->format('h:i:s A') }} - {{ \Carbon\Carbon::parse($appointment->end)->format('h:i:s A') }}</small></span>
		            
		            </div>
					@if(isset($fromPatient))
                     <button type="submit" class="btn btn-danger btn-sm" form="form-delete" formaction="{!! url('/appointments/'.$appointment->id) !!}">Cancelar cita</button>
                     @endif  
		            <!-- /.info-box-content -->
		          </a>
		@empty
    		<p>Aun no hay citas agendadas.</p>
		@endforelse
	</div>
	<div class="col-sm-6 col-xs-12">
		<h2>Historial de consultas</h2>
		@forelse($initAppointments as $appointment)
			
				<a class="info-box cita-item" href="{{ (isset($fromPatient) || auth()->id() != $appointment->user->id) ? '#': '/medic/appointments/'.$appointment->id.'/edit'}}" style="text-align: left;">
		            <span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span>

		            <div class="info-box-content">
		              <span class="info-box-text">{{ $appointment->title }} con <small>Dr. {{ $appointment->user->name }}</small></span>
		              <span class="info-box-number">{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}<small></small></span>
		              
		            </div>
		            <!-- /.info-box-content -->
		          </a>
		@empty
    		<p>Aun no hay citas iniciadas.</p>
		@endforelse
	</div>

</div>



