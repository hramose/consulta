@extends('layouts.app-patient')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
   <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
@endsection
@section('content')
	
	
	 <section class="content">
	     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ $patient->first_name }}</h3> 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-calendar margin-r-5"></i> {{ $appointment->title }}</strong>

              <p class="text-muted">
                {{ age($patient->birth_date) }} - {{ trans('utils.gender.'.$patient->gender) }} - {{ $patient->phone }}
              </p>
							<a href="/appointments/{{ $appointment->id }}/print" target="_blank" class="btn btn-default" style="position: absolute; right: 18px; top: 3px; z-index: 99"><i class="fa fa-print"></i> Imprimir</a>
            </div>
          </div>
          <div class="row">
			<div class="col-md-12">
		        <div class="nav-tabs-custom">
		            <ul class="nav nav-tabs">
		              <li><a href="#history" data-toggle="tab">Historial</a></li>
		              <li class="active"><a href="#notes" data-toggle="tab">Notas de padecimiento</a></li>
									 <li><a href="#physical" data-toggle="tab">Examen Fisico</a></li>
									 <li><a href="#labexam" data-toggle="tab">Examen Laboratorio</a></li>
		                <li><a href="#diagnostic" data-toggle="tab">Diagnostico y Tratamiento</a></li>
		              
		            </ul>
		            <div class="tab-content">
		              <div class="tab-pane" id="history">
		                 <div class="row">
							<div class="col-md-6">
		                 		<history :history="{{ $history }}" :appointments="{{ $appointments }}" :read="true"></history>
		                	</div>
		                	<div class="col-md-6">

								<div class="box box-info">

								    <div class="box-header with-border">
								      <h3 class="box-title">Medicamentos Activos</h3>

								      <div class="box-tools pull-right">
								        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								        </button>
								      </div>
								      <!-- /.box-tools -->
								    </div>
								    <!-- /.box-header -->
								    <div class="box-body">
								     
								       
										<medicines :medicines="{{  $patient->medicines }}" :patient_id="{{  $patient->id }}" :read="true"></medicines>	
								      
								        
								    </div>
								    <!-- /.box-body -->
								</div> 
		                		
		                		 <div class="box box-warning">

								    <div class="box-header with-border">
								      <h3 class="box-title">Archivos</h3>

								      <div class="box-tools pull-right">
								        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								        </button>
								      </div>
								      <!-- /.box-tools -->
								    </div>
								    <!-- /.box-header -->
								    <div class="box-body">
								      <ul id="files-list" class="todo-list ui-sortable">
								        @foreach($files as $file)
								        <li>
								          <!-- todo text -->
								          <a href="{{ Storage::url($file) }}" title="{{ $file }}" target="_blank"><span class="text">{{  explode("/",$file)[3]  }}</span></a>
								          <!-- General tools such as edit or delete-->
								          <div class="tools">
								            
								            
								          </div>
								        </li>
								        @endforeach
								      </ul>
								    
								       
								      
								        
								    </div>
								    <!-- /.box-body -->
								</div> 
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
									     
									       
											<signs :signs="{{ $patient->vitalSigns }}"></signs>
									      
									        
									    </div>
									    <!-- /.box-body -->
									</div>
									
								</div>
								<div class="col-md-6">
								
		              				<diseasenotes :notes="{{ $appointment->diseaseNotes }}" :read="true"></diseasenotes>
			              		</div>
			              	</div>
		              </div>
		              <!-- /.tab-pane -->
					  <div class="tab-pane" id="physical">
		              		<physicalexam :physical="{{ $appointment->physicalExams }}" :read="true"></physicalexam>
									</div>
									 <!-- /.tab-pane -->
					   <div class="tab-pane" id="labexam">
						   
		              		<lab-exams :exams="{{ $appointment->labexams->load('results') }}" :patient_id="{{ $appointment->patient->id }}" :appointment_id="{{ $appointment->id }}" :read="true"></lab-exams>
		              </div>
		              <!-- /.tab-pane -->
		               <div class="tab-pane" id="diagnostic">

								<div class="row">
									<div class="col-md-12">
			              				<diagnostics :diagnostics="{{ $appointment->diagnostics }}" :appointment_id="{{ $appointment->id }}" :read="true"></diagnostics>
			              			</div>
			              		</div>
			              		<div class="row">
									<div class="col-md-12">
													<a href="/appointments/{{ $appointment->id }}/treatment/print" target="_blank" class="btn btn-default" style="position: absolute; right: 18px; top: 6px; z-index: 99"><i class="fa fa-print"></i> Imprimir Receta</a>
			              				<treatments :treatments="{{ $appointment->treatments }}" :appointment_id="{{ $appointment->id }}" :read="true"></treatments>
			              			</div>
			              		</div>
			              		<div class="row">
									<div class="col-md-12">
			              				
			              			</div>
			              			<div class="col-md-12">
			              				<instructions :appointment="{{ $appointment }}" :read="true"></instructions>
			              			</div>
			              		</div>
		              </div>
		               <!-- /.tab-pane -->
		               
		            </div>
		            <!-- /.tab-content -->
		        </div>
		          <!-- /.nav-tabs-custom -->
		    </div>
		   
		</div>
				
	</section>
	
@endsection
@section('scripts')
<script src="/js/plugins/iCheck/icheck.min.js"></script>
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="/js/plugins/ajaxupload.js"></script>
<script>
  $(function () {

  	$("#UploadFile").ajaxUpload({
      url : "/medic/patients/files",
      name: "file",
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


    $('.btn-finish-appointment').on('click', function (e) {
        
        swal({
            title: 'Terminando Consulta!',
            text: "Desea agendar un seguimiento a este paciente o volver a la agenda del día?",
            //type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Agendar seguimiento',
            cancelButtonText: 'Volver a agenda',
            //confirmButtonClass: 'btn btn-success',
            //cancelButtonClass: 'btn btn-danger',
            //buttonsStyling: false
          }).then(function () {
             window.location = '/medic/appointments/create?p={{ $patient->id }}';
          }, function (dismiss) {
            // dismiss can be 'cancel', 'overlay',
            // 'close', and 'timer'
            if (dismiss === 'cancel') {

               window.location = '/medic/appointments';
            }
          })
    });
  
  });
</script>
@endsection
