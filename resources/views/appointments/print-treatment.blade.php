@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')
 <section class="content">
	
		  <!-- Main content -->
		  <section class="invoice">
		    <!-- title row -->
		    <div class="row">
		      <div class="col-xs-12">
		        <h2 class="page-header">
		          <i class="fa fa-globe"></i> {{ config('app.name', 'Laravel') }}
		          <small class="pull-right">Fecha: {{ \Carbon\Carbon::now() }}</small>
		        </h2>
		      </div>
		      <!-- /.col -->
		    </div>
		    <!-- info row -->
		    <div class="row invoice-info">
		      <div class="col-sm-4 invoice-col">
		        Paciente
		        <address>
		          <strong>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</strong><br>
		          {{ trans('utils.gender.'.$appointment->patient->gender) }}<br>
		          {{ age($appointment->patient->birth_date) }}<br>
		          Phone: {{ $appointment->patient->phone }}<br>
		          Email: {{ $appointment->patient->email }}
		        </address>
		      </div>
		      <!-- /.col -->
		      <div class="col-sm-4 invoice-col">
		      {{ config('app.name', 'Laravel') }}
		        <address>
		          <strong>{{ $appointment->title }}</strong><br>
		          <b>Fecha:</b> {{ $appointment->date }}<br>
		          <b>Medico:</b> {{ auth()->user()->name }}<br>
		          <b>Código de Médico:</b> {{ auth()->user()->medic_code }}<br>
		          <b>Especialidad:</b> {{ auth()->user()->getSpecialityName() }}
		          
		        </address>
		      </div>
		      <!-- /.col -->
		      <div class="col-sm-4 invoice-col">
		        <b>{{ $appointment->office->name }}</b><br>
		        {{ $appointment->office->address }}, {{ $appointment->office->province }}<br>
		        <b>Tel:</b> {{ $appointment->office->phone }}<br>
		        <b>Generado por:  {{ config('app.name', 'Laravel') }}</b><br>
		        <b>Impreso por: </b> {{ auth()->user()->name }}<br>
		        
		      </div>
		      <!-- /.col -->
		    </div>
		    <!-- /.row -->

		    <!-- Table row -->
		    <div class="row">
		      <div class="col-xs-12">
		      	
		      	<h4>Tratamiento</h4>
		      	@foreach($appointment->treatments as $treatment)
					<div><b>{{ $treatment->name }}</b>: {{ $treatment->comments }}</div>
		      	@endforeach
		      	
		      	  <div><b>Recomendaciones  Médicas</b>:{{ $appointment->medical_instructions }} </div>
		      </div>
		    </div>
		    <!-- /.row -->

		    <div class="row">
		      <!-- accepted payments column -->
		      <div class="col-xs-6">
		        <p class="lead"></p>
		       
				<hr style="margin-top: 40px; margin-bottom: 0;">
		        <div style="text-align: center;">
		        	<small style="text-transform: uppercase;">{{ auth()->user()->name }}</small>
		        </div>
		        
		      </div>
		      <!-- /.col -->
		      <div class="col-xs-6">
		        
		      </div>
		      <!-- /.col -->
		    </div>
		    <!-- /.row -->
		  </section>
		  <!-- /.content -->
		

 
</section>
 @endsection
 @section('scripts')
 <script>
 	 function printSummary() {
            window.print();
        }
        window.onload = printSummary;
 </script>
 @endsection