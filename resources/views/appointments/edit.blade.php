@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
   <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
@endsection
@section('content')
	
	
	 <section class="content">
	     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ $appointment->patient->first_name }}</h3> <a href="#" class="btn-finish-appointment btn btn-success" style="position: absolute; right: 18px; top: 3px; z-index: 99">Terminar Consulta</a>
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
		              <li class="{{ isset($tab) ? '' : 'active' }}"><a href="#notes" data-toggle="tab">Notas de padecimiento</a></li>
		               <li><a href="#physical" data-toggle="tab">Examen Fisico</a></li>
		                <li><a href="#diagnostic" data-toggle="tab">Diagnostico y Tratamiento</a></li>
		                <li><a href="#invoice" data-toggle="tab" class="invoice-tab">Facturar</a></li>
		                <li class="{{ isset($tab) ? ($tab =='notesAppointment') ? 'active' : '' :'' }}"><a href="#notesAppointment" data-toggle="tab">Notas <span data-toggle="tooltip" title="" class="badge bg-green" data-original-title="{{ $appointment->notes->count() }} Notas">{{ $appointment->notes->count() }}</span></a></li>
		            </ul>
		            <div class="tab-content">
		              <div class="tab-pane" id="history">
		                 <div class="row">
							<div class="col-md-6">
		                 		<history :history="{{ $history }}" :appointments="{{ $appointments }}"></history>
		                	</div>
		                	<div class="col-md-6">
		          				 @include('patients/partials/medicines', ['patient' => $appointment->patient]) 
		                		 @include('patients/partials/files', ['files' => $files]) 
		                 	</div>
		                 </div>
		              </div>
		              <!-- /.tab-pane -->
		              <div class="{{ isset($tab) ? '' : 'active' }} tab-pane" id="notes">
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
								
		              				<diseasenotes :notes="{{ $appointment->diseaseNotes }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}"></diseasenotes>
			              		</div>
			              	</div>
		              </div>
		              <!-- /.tab-pane -->
					  <div class="tab-pane" id="physical">
		              		<physicalexam :physical="{{ $appointment->physicalExams }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}"></physicalexam>
		              </div>
		              <!-- /.tab-pane -->
		               <div class="tab-pane" id="diagnostic">

								<div class="row">
									<div class="col-md-12">
			              				<diagnostics :diagnostics="{{ $appointment->diagnostics }}" :appointment_id="{{ $appointment->id }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}"></diagnostics>
			              			</div>
			              		</div>
			              		<div class="row">
									<div class="col-md-12">
									    <a href="/medic/appointments/{{ $appointment->id }}/treatment/print" target="_blank" class="btn btn-default" style="position: absolute; right: 18px; top: 6px; z-index: 99"><i class="fa fa-print"></i> Imprimir Receta</a>
			              				<treatments :treatments="{{ $appointment->treatments }}" :appointment_id="{{ $appointment->id }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}"></treatments>
			              			</div>
			              		</div>
			              		<div class="row">
									<div class="col-md-12">
			              				
			              			</div>
			              			<div class="col-md-12">
			              				<instructions :appointment="{{ $appointment }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}"></instructions>
			              			</div>
			              		</div>
		              </div>
		               <!-- /.tab-pane -->
		                <div class="tab-pane" id="invoice">
								@if(\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1)
									 <div class="callout callout-danger">
					                    <h4>Información importante!</h4>

					                    <p>No se puede facturar en consultas pasadas o finalizadas... para ver todas tus facturas, ingresa al modulo facturación</p>
					                </div>

								@else
								<div class="row">
									<div class="col-md-12">
			              				<invoice-form :appointment_id="{{ $appointment->id }}" :office_id="{{ $appointment->office->id }}" ></invoice-form>
			              			</div>
			              		</div>
			              		<div class="row">
									<div class="col-md-12">
										<h3>Facturas del Día</h3>
			              				<invoice-list :invoices="{{ auth()->user()->invoices()->whereDate('created_at',\Carbon\Carbon::now()->ToDateString())->orderBy('created_at','DESC')->get() }}" :total="{{ auth()->user()->invoices()->whereDate('created_at',\Carbon\Carbon::now()->ToDateString())->sum('total') }}"></invoice-list>
			              			</div>
			              		</div>
			              		@endif
			              		
		              </div>
		               <div class="{{ isset($tab) ? ($tab =='notesAppointment') ? 'active' : '' :'' }} tab-pane" id="notesAppointment">
		              		<div class="row">
								<div class="col-md-12">
									<notes :notes="{{ $appointment->notes }}" :appointment_id="{{ $appointment->id }}" ></notes>
									
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
		    	<a href="/medic/appointments/{{ $appointment->id }}/print" target="_blank" class="btn btn-default" style="position: absolute; right: 18px; top: 3px; z-index: 99"><i class="fa fa-print"></i> Imprimir</a>
		    	<summary-appointment :history="{{ $history }}" :medicines="{{ $appointment->patient->medicines }}" :notes="{{ $appointment->diseaseNotes }}" :exams="{{ $appointment->physicalExams }}" :diagnostics="{{ $appointment->diagnostics }}" :treatments="{{ $appointment->treatments }}" instructions="{{ $appointment->medical_instructions }}" ></summary-appointment>
		      
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
          	  $.ajax({
		            type: 'PUT',
		            url: '/medic/appointments/{{ $appointment->id }}/finished',
		            data: {},
		            success: function (resp) {
		              
		             console.log('cita finalizada');
		             window.location = '/medic/appointments/create?p={{ $appointment->patient->id }}';
		             
		            },
		            error: function () {
		              console.log('error finalizando citan');

		            }

		        });

            
          }, function (dismiss) {
            // dismiss can be 'cancel', 'overlay',
            // 'close', and 'timer'
            if (dismiss === 'cancel') {

               $.ajax({
		            type: 'PUT',
		            url: '/medic/appointments/{{ $appointment->id }}/finished',
		            data: {},
		            success: function (resp) {
		              
		             console.log('cita finalizada');
		            window.location = '/medic/appointments';
		             
		            },
		            error: function () {
		              console.log('error finalizando citan');

		            }

		        });

               
            }
          })
    });
  
  });
</script>
@endsection
