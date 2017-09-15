@extends('layouts.app-patient')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')
 <section class="content">
	
		  <!-- Main content -->
		  <section class="invoice">
				<!-- title row -->
				<!-- info row -->
				<div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <div class="logo">
            <img src="{{ getLogo($appointment->office) }}" alt="logo">
          </div>  
        
  
          
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <h2>{{ $appointment->office->name }}</h2>
          <address>
          {{ $appointment->office->type }}<br>
          {{ $appointment->office->canton }}, {{ $appointment->office->province }}<br>
          {{ $appointment->office->address }}<br>
          <b>Tel:</b> {{ $appointment->office->phone }}<br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
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
      <div class="row invoice-patient">
        <div class="col-xs-4 invoice-col invoice-left">     
            <b>Paciente:</b> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}. {{ trans('utils.gender.'.$appointment->patient->gender) }}.<br>
						 <b>Fecha Nacimiento:</b> {{ age($appointment->patient->birth_date) }}<br>
		        
						<b>Fecha Consulta:</b> {{ $appointment->date }}<br>
        </div>
        <div class="col-xs-4 invoice-col invoice-right">
           
        </div>
        <div class="col-xs-4 invoice-col invoice-right">
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
			      	<summary-appointment :history="{{ $history }}" :medicines="{{ $appointment->patient->medicines }}" :notes="{{ $appointment->diseaseNotes }}" :exams="{{ $appointment->physicalExams }}" :diagnostics="{{ $appointment->diagnostics }}" :treatments="{{ $appointment->treatments }}" instructions="{{ $appointment->medical_instructions }}">
			      		Historia Clínica del Paciente
			      	</summary-appointment>
		      	</div>
		      </div>
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