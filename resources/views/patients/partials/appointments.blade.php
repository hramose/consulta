<div class="row">
	<div class="col-sm-6 col-xs-12">
		<h2>Consultas agendadas</h2>
		@forelse($scheduledAppointments as $appointment)
			
				<a class="info-box" href="{{ isset($fromPatient) ? '#': '/medic/appointments/'.$appointment->id.'/edit'}}" style="text-align: left;">
		            <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>

		            <div class="info-box-content">
		              <span class="info-box-text">{{ $appointment->title }}</span>
		              <span class="info-box-number">{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}</span>
		              <span class="info-box-text"><small>{{ \Carbon\Carbon::parse($appointment->start)->format('h:i:s A') }} - {{ \Carbon\Carbon::parse($appointment->end)->format('h:i:s A') }}</small></span>
		              <span class="info-box-text"><small>Dr. {{ $appointment->user->name }}</small></span>
		            </div>
		            <!-- /.info-box-content -->
		          </a>
		@empty
    		<p>Aun no hay citas agendadas.</p>
		@endforelse
	</div>
	<div class="col-sm-6 col-xs-12">
		<h2>Consultas iniciadas</h2>
		@forelse($initAppointments as $appointment)
			
				<a class="info-box" href="{{ isset($fromPatient) ? '#': '/medic/appointments/'.$appointment->id.'/edit'}}" style="text-align: left;">
		            <span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span>

		            <div class="info-box-content">
		              <span class="info-box-text">{{ $appointment->title }}</span>
		              <span class="info-box-number">{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}<small></small></span>
		              <span class="info-box-text"><small>Dr. {{ $appointment->user->name }}</small></span>
		            </div>
		            <!-- /.info-box-content -->
		          </a>
		@empty
    		<p>Aun no hay citas iniciadas.</p>
		@endforelse
	</div>
</div>


