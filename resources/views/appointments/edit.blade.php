@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
@endsection
@section('content')
	
	
	 <section class="content">
	     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ $appointment->patient->first_name}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-calendar margin-r-5"></i> {{ $appointment->title }}</strong>

              <p class="text-muted">
                {{ age($appointment->patient->birth_date) }} - {{ trans('utils.gender.'.$appointment->patient->gender) }} - {{ $appointment->patient->phone }}
              </p>

            </div>
          </div>
		<div class="row">
			<div class="col-md-9">
		        <div class="nav-tabs-custom">
		            <ul class="nav nav-tabs">
		              <li><a href="#history" data-toggle="tab">Historial</a></li>
		              <li class="active"><a href="#notes" data-toggle="tab">Notas de padecimiento</a></li>
		               <li><a href="#physical" data-toggle="tab">Examen Fisico</a></li>
		                <li><a href="#diagnostic" data-toggle="tab">Diagnostico</a></li>
		            </ul>
		            <div class="tab-content">
		              <div class="tab-pane" id="history">
		                 <div class="row">
							<div class="col-md-6">
		                 		<history :history="{{ $appointment->patient->history }}"></history>
		                	</div>
		                	<div class="col-md-6">
		          				 @include('patients/partials/medicines', ['patient' => $appointment->patient])
				                 @include('patients/partials/files', ['files' => $files])
		                 	</div>
		                 </div>
		              </div>
		              <!-- /.tab-pane -->
		              <div class="active tab-pane" id="notes">
		              		<div class="row">
        						<div class="col-md-6">
        							<div class="box box-info">
									    <div class="box-header with-border">
									      <h3 class="box-title">Signos vitales actuales</h3>

									      <div class="box-tools pull-right">
									        
									      </div>
									      <!-- /.box-tools -->
									    </div>
									    <!-- /.box-header -->
									    <div class="box-body">
									     
									       
										<signs :signs="{{ $appointment->patient->vitalSigns }}"></signs>	
									      
									        
									    </div>
									    <!-- /.box-body -->
									</div>
        							
        						</div>
        						<div class="col-md-6">
		              				<diseasenotes :notes="{{ $appointment->diseaseNotes }}"></diseasenotes>
			              		</div>
			              	</div>
		              </div>
		              <!-- /.tab-pane -->
					  <div class="tab-pane" id="physical">
		              		<physicalexam :physical="{{ $appointment->physicalExams }}"></physicalexam>
		              </div>
		              <!-- /.tab-pane -->
		               <div class="tab-pane" id="diagnostic">

  							<div class="row">
        						<div class="col-md-12">
		              				<diagnostics :diagnostics="{{ $appointment->diagnostics }}" :appointment_id="{{ $appointment->id }}"></diagnostics>
		              			</div>
		              		</div>
		              		<div class="row">
        						<div class="col-md-12">
		              				
		              			</div>
		              			<div class="col-md-12">
		              				<instructions :appointment="{{ $appointment }}" ></instructions>
		              			</div>
		              		</div>
		              </div>
		               <!-- /.tab-pane -->
		            </div>
		            <!-- /.tab-content -->
		        </div>
		          <!-- /.nav-tabs-custom -->
		    </div>
		    <div class="col-md-3" style="position: relative;">
		    	<a href="/medic/appointments/{{ $appointment->id }}/print" target="_blank" class="btn btn-default" style="position: absolute; right: 18px; top: 3px; z-index: 99"><i class="fa fa-print"></i> Print</a>
	           <summary-appointment :history="{{ $appointment->patient->history }}" :medicines="{{ $appointment->patient->medicines }}" :notes="{{ $appointment->diseaseNotes }}" :exams="{{ $appointment->physicalExams }}" :diagnostics="{{ $appointment->diagnostics }}" instructions="{{ $appointment->medical_instructions }}" ></summary-appointment>
	        </div>
	    </div>
	</section>
	
@endsection
@section('scripts')
<script src="/js/plugins/iCheck/icheck.min.js"></script>
<script src="/js/plugins/ajaxupload.js"></script>
<script>
  $(function () {

  	$("#UploadFile").ajaxUpload({
      url : "/medic/patients/files",
      name: "file",
      data: {patient_id: {{ $appointment->patient->id }} },
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

          $('#infoBox').addClass('alert-success').html('El Archivo se ha guardado con exito!!').show();
	          setTimeout(function()
	          { 
	          	$('#infoBox').removeClass('alert-success').hide();
	          },3000);
			
		
          var li = "<li><a href='/storage/"+ result +"'' title='"+ result.split("/")[3]+ "' target='_blank'><span class='text'>"+ result.split("/")[3] +"</span></a>"+
          "<div class='tools'>"+
            "<i class='fa fa-trash-o delete' data-file='"+result+"'></i>"+
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

        $.post(url,{file: btn_delete.attr("data-file") }, function(data){
            btn_delete.parents('li').fadeOut("slow");
        });
    }
  
  });
</script>
@endsection
