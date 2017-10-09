<div class="box box-solid">
	<div class="box-header with-border">
	  <i class="fa fa-book"></i>

	  <h3 class="box-title"><slot>Autocontrol del Paciente</slot></h3>
   
	</div>
	<!-- /.box-header -->
	<div class="box-body summary-flex">
	  <dl class="summary-dl">
      
      <dt class="text-aqua"><h4>Control del Presión</h4></dt>
      <dd>
        @foreach($patient->pressures()->orderBy('created_at','DESC')->limit(10)->get() as $pressure)
            <div><span>P.S: {{ $pressure->ps }} / P.D: {{ $pressure->pd }} - {{ $pressure->date_control }} {{ $pressure->time_control }}</span></div>
        @endforeach
       
       
      </dd>
      <dt class="text-aqua"><h4>Control del Azúcar</h4></dt>
      <dd>
        @foreach($patient->sugars()->orderBy('created_at','DESC')->limit(10)->get() as $sugar)
            <div><span>Glicemia: {{ $sugar->glicemia }} - {{ $sugar->date_control }} {{ $sugar->time_control }}</span></div>
        @endforeach
       
       
      </dd>
      <dt class="text-aqua"><h4>Medicamentos Activos</h4></dt>
      <dd >
         @foreach($patient->medicines as $medicine)
            <div><span>{{ $medicine->name }}</span></div>
        @endforeach
      </dd>
      <dt class="text-aqua"><h4>Alergias</h4></dt>
      <dd >
        @foreach($history->allergies as $allergy)
            <div><span>{{ $allergy->name }}</span></div>
        @endforeach 
      </dd>
     
    </dl>
    
    
	</div>

</div>
