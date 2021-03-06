@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
 <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 
<link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
@endsection
@section('content')
	<div id="infoBox" class="alert"></div> 
	@include('layouts/partials/header-pages',['page'=>'Pacientes'])
	<div class="row">
		<div class="col-md-8">
			<!-- <a href="{{ url('/medic/appointments/create?p='.$patient->id) }}" class="btn btn-success" style="margin-left: 15px;margin-top: 5px;">Crear cita a este paciente</a> -->
			@if(auth()->user()->hasSubscription())  
				@if(!$monthlyCharge->count())
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#initAppointment" data-backdrop="static" data-patient="{{ $patient->id }}" data-patientname="{{ $patient->first_name }} {{ $patient->last_name }}" title="Iniciar consulta con este paciente" style="margin-left: 15px;margin-top: 5px;"><i class="fa fa-list"></i> Iniciar consulta con este paciente
                          </button>
				@else
						<a href="#" data-toggle="modal" data-target="#modalPendingPayments" class="btn btn-success" title="Iniciar Consulta" style="margin-left: 15px;margin-top: 5px;"><i class="fa fa-list"></i> Iniciar consulta con este paciente</a>
				@endif
			@else
				<a href="#" data-toggle="modal" data-target="#modalSubscription" class="btn btn-success" title="Iniciar Consulta" style="margin-left: 15px;margin-top: 5px;"><i class="fa fa-list"></i> Iniciar consulta con este paciente</a>
			@endif
		
		</div>
	</div>
	<section class="content">
      
      <div class="row">
        <div class="col-md-4">
			
          @include('medic/patients/partials/photo', ['patient' => $patient])
		  @include('medic/patients/partials/files', ['files' => $files])
		 
          
         
        </div>
		 
		<div class="col-md-8">
			<div class="nav-tabs-custom">
	            <ul class="nav nav-tabs">
	              <li class="{{ isset($tab) ? ($tab =='basic') ? 'active' : '' : 'active' }}"><a href="#basic" data-toggle="tab">Información Básica</a></li>
	              <li class="{{ isset($tab) ? ($tab =='history') ? 'active' : '' : '' }}"><a href="#history" data-toggle="tab">Historial Médico</a></li>
	              <li class="{{ isset($tab) ? ($tab =='appointments') ? 'active' : '' : '' }}"><a href="#appointments" data-toggle="tab">Consultas</a></li>
	             
	              
	            </ul>
	            <div class="tab-content">
	              	<div class="{{ isset($tab) ? ($tab =='basic') ? 'active' : '' : 'active' }} tab-pane" id="basic">
						<form method="POST" action="{{ url('/medic/patients/'.$patient->id) }}" class="form-horizontal">
					         {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
					         @include('medic/patients/partials/form',['buttonText' => 'Actualizar Paciente'])
					    </form>

				    </div>
				    <!-- /.tab-pane -->
				    <div class="{{ isset($tab) ? ($tab =='history') ? 'active' : '' : '' }} tab-pane" id="history">

						<div class="row">
						  @foreach($summaryAppointments as $lastAppointment)
							<div class="col-md-4">

							<summary-appointment history="" :medicines="{{ $lastAppointment->patient->medicines }}" :notes="{{ $lastAppointment->diseaseNotes }}" :exams="{{ $lastAppointment->physicalExams }}" :diagnostics="{{ $lastAppointment->diagnostics }}" :treatments="{{ $lastAppointment->treatments }}" instructions="{{ $lastAppointment->medical_instructions }}" :labexams="{{ $lastAppointment->labexams }}">
									Historia Clinica {{ $lastAppointment->id }} - {{ \Carbon\Carbon::parse($lastAppointment->start)->format('Y-m-d H:i') }}						</summary-appointment>
												 
		                	</div>
		                  @endforeach
		                 </div> 
					    <history :history="{{ $patient->history }}" ></history>	
				    </div>
				    <!-- /.tab-pane -->
				    <div class="{{ isset($tab) ? ($tab =='appointments') ? 'active' : '' : '' }} tab-pane" id="appointments">
						
					      @include('medic/patients/partials/appointments')
					    
				    </div>
				    <!-- /.tab-pane -->
				    

				              
				             
	            </div>
	            <!-- /.tab-content -->
	        </div>
	          <!-- /.nav-tabs-custom -->
			 @include('medic/patients/partials/medicines', ['patient' => $patient])	
			
		</div>

	  </div>
	</section>

	@include('medic/patients/partials/initAppointment')
	@include('layouts/partials/modal-subscriptions')
	@include('layouts/partials/modal-pending-payments')
@endsection
@section('scripts')
<script src="/js/plugins/select2/select2.full.min.js"></script>  
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/moment/locale/es.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script src="/js/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/js/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/js/plugins/ajaxupload.js"></script>
<script src="/js/plugins/fullcalendar/jquery-ui.min.js"></script>
<script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/fullcalendar/locale/es.js"></script> 
<script src="{{ elixir('/js/patients.min.js') }}"></script>
<script>
  $(function () {
  
	$("[data-mask]").inputmask();
	
	$('#datetimepickerLabResult').datetimepicker({
			format:'YYYY-MM-DD',
            locale: 'es',
            
         });

     $("#UploadPhoto").ajaxUpload({
      url : $("#UploadPhoto").data('url'),
      name: "photo",
      data: {patient_id: {{ $patient->id }} },
      onSubmit: function() {
          $('#infoBox').html('Uploading ... ');

      },
      onComplete: function(result) {

          if(result ==='error'){

          	$('#infoBox').addClass('alert-danger').html('Error al subir archivo. Tipo no permitido!!').show();
		          setTimeout(function()
		          { 
		          	$('#infoBox').removeClass('alert-danger').hide();
		          },3000);

		     return

          }

          $('#infoBox').addClass('alert-success').html('La foto se ha guardado con exito!!').show();
	          setTimeout(function()
	          { 
	          	$('#infoBox').removeClass('alert-success').hide();
	          },3000);
				d = new Date();
				
	          $('.profile-user-img').attr('src','/storage/'+ result+'?'+d.getTime());
			
      }
  });

    $("#UploadFile").ajaxUpload({
      url : "/medic/patients/files",
      name: "file",
      data: {patient_id: {{ $patient->id }} },
      onSubmit: function() {
          $('#infoBox').html('Uploading ... ');

      },
      onComplete: function(result) {
		  var result = JSON.parse(result);
          if(result ==='error'){

          	$('#infoBox').addClass('alert-danger').html('Error al subir archivo. Tipo no permitido!!').show();
		          setTimeout(function()
		          { 
		          	$('#infoBox').removeClass('alert-danger').hide();
		          },3000);

		     return

          }

          $('#infoBox').addClass('alert-success').html('El Archivo se ha guardado con exito!!').show();
	          setTimeout(function()
	          { 
	          	$('#infoBox').removeClass('alert-success').hide();
	          },3000);
			
		
          var li = "<li><a href='/storage/"+ result.file +"' title='"+ result.file.split("/")[3]+ "' target='_blank'><span class='text'>"+ result.file.split("/")[3] +"</span></a>"+
          "<div class='tools'>"+
            "<i class='fa fa-trash-o delete' data-file='"+result.file+"' data-id='"+result.id+"'></i>"+
          "</div></li>";

          $('#files-list').append(li);

          $('#files-list').find('li').find('.delete').on('click',deleteFile);

         


      }
  });
     $('#files-list').find('li').find('.delete').on('click',deleteFile);

    function deleteFile()
    {
        var btn_delete = $(this),
            url = "/medic/patients/files/delete";

        $.post(url,{file: btn_delete.attr("data-file"), id: btn_delete.attr("data-id")  }, function(data){
            btn_delete.parents('li').fadeOut("slow");
        });
    }



  });
</script>
@endsection

