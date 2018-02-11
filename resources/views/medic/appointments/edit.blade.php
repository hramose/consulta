@extends('layouts.app')
@section('css')
 <link rel="stylesheet" href="/js/plugins/iCheck/all.css">
	 <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
	 <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 
@endsection
@section('content')
	
	
	 <section class="content">
	     <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">{{ $patient->first_name }}</h3> <a href="#" class="btn-finish-appointment btn btn-success" style="position: absolute; right: 18px; top: 3px; z-index: 99">Terminar Consulta</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-calendar margin-r-5"></i> {{ $appointment->title }}</strong>

              <p class="text-muted">
                {{ age($patient->birth_date) }} - {{ trans('utils.gender.'.$patient->gender) }} - {{ $patient->phone }}
              </p>

            </div>
          </div>
          <div class="row">
			<div class="col-md-9">
		        <div class="nav-tabs-custom">
		            <ul class="nav nav-tabs">
		              <li class="active"><a href="#history" data-toggle="tab">Historial</a></li>
		              <li ><a href="#notes" data-toggle="tab">Notas de padecimiento</a></li>
		               <li><a href="#physical" data-toggle="tab">Examen Fisico</a></li>
					   <li><a href="#labexam" data-toggle="tab">Pruebas Diagnósticas</a></li>
		                <li><a href="#diagnostic" data-toggle="tab">Diagnostico y Tratamiento</a></li>
		                <li><a href="#invoice" data-toggle="tab" class="invoice-tab">Facturar</a></li>
		                
		            </ul>
		            <div class="tab-content">
		              <div class="active tab-pane" id="history">
					  <div class="row">
						  @foreach($appointments as $lastAppointment)
							<div class="col-md-4">

							<summary-appointment history="" :vital_signs="{{ $lastAppointment->vitalSigns }}" :medicines="{{ $lastAppointment->patient->medicines }}" :notes="{{ $lastAppointment->diseaseNotes }}" :exams="{{ $lastAppointment->physicalExams }}" :diagnostics="{{ $lastAppointment->diagnostics }}" :treatments="{{ $lastAppointment->treatments }}" instructions="{{ $lastAppointment->medical_instructions }}" :labexams="{{ $lastAppointment->labexams }}">
									Historia Clinica {{ $lastAppointment->id }} - {{ \Carbon\Carbon::parse($lastAppointment->start)->format('Y-m-d H:i') }}						</summary-appointment>
												 
		                	</div>
		                  @endforeach
		                 </div> 
					  
		                 <div class="row">
							<div class="col-md-6">

								<history :history="{{ $history }}" ></history>
												 
		                	</div>
		                	<div class="col-md-6">
								 @include('medic/patients/partials/summary-control', ['patient' => $patient,'history'=> $history])
		          				 @include('medic/patients/partials/medicines-medic', ['patient' => $patient]) 
											 
												 
		                 	</div>
		                 </div>
		              </div>
		              <!-- /.tab-pane -->
		              <div class="tab-pane" id="notes">
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
															
															@if($appointment->vitalSigns)	
															<signs :signs="{{ $appointment->vitalSigns }}"></signs>
															@endif
																	
															</div>
															<!-- /.box-body -->
													</div>
													
												</div>
												<div class="col-md-6">
												
																	<diseasenotes :notes="{{ $appointment->diseaseNotes }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}"></diseasenotes>
																	@if( \Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1 )
																		@include('medic/patients/partials/files', ['files' => $files,'read' => true])
																	@else
																		@include('medic/patients/partials/files', ['files' => $files])
																	@endif

												</div>
											</div>
		              </div>
		              <!-- /.tab-pane -->
					  <div class="tab-pane" id="physical">
		              		<physicalexam :physical="{{ $appointment->physicalExams }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}"></physicalexam>
		              </div>
					   <!-- /.tab-pane -->
					   <div class="tab-pane" id="labexam">
					   <div class="row">
							<div class="col-md-6">
							  <lab-exams :patient_id="{{ $appointment->patient_id }}" :appointment_id="{{ $appointment->id }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}"></lab-exams>
							</div>
							<div class="col-md-6">
							<lab-results :patient_id="{{ $appointment->patient_id }}" :read="{{ (\Carbon\Carbon::now()->ToDateString() > $appointment->date || $appointment->finished == 1) ? 'true' : 'false' }}" :results="{{$patient->labresults }}"></lab-results>

							</div>
						</div>
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

								@elseif( auth()->user()->fe && (!auth()->user()->configFactura || !existsCertFile(auth()->user())) )
									<div class="callout callout-danger">
					                    <h4>Información importante!</h4>

					                    <p>No tienes configurado los parametros para la factura electronica. Por favor configuralos para poder continuar. <a href="/medic/account/edit?tab=fe" title="Ir a configurar Factura Electronica"><b>Configurar Factura Electrónica</b></a></p>
					                </div>
								@else
								<div class="row">
									<div class="col-md-12">
			              				<invoice-form :appointment_id="{{ $appointment->id }}" :patient_id="{{ $appointment->patient_id }}" :office_id="{{ $appointment->office->id }}" office_type="{{ $appointment->office->type }}" facturar_a="{{ $appointment->office->bill_to }}"></invoice-form>
			              			</div>
			              		</div>
			              		<!-- <div class="row">
									<div class="col-md-12">
										<h3>Facturas del Día</h3>
			              				<invoice-list :invoices="{{ auth()->user()->invoices()->with('clinic')->whereDate('created_at',\Carbon\Carbon::now()->ToDateString())->orderBy('created_at','DESC')->get() }}" :total="{{ auth()->user()->invoices()->whereDate('created_at',\Carbon\Carbon::now()->ToDateString())->sum('total') }}"></invoice-list>
			              			</div>
			              		</div> -->
			              		@endif
			              		
		              </div>
		               
		               <!-- /.tab-pane -->
		            </div>
		            <!-- /.tab-content -->
		        </div>
		          <!-- /.nav-tabs-custom -->
		    </div>
		    <div class="col-md-3" style="position: relative;">
				<a href="/medic/appointments/{{ $appointment->id }}/print" target="_blank" class="btn btn-default" style="position: absolute; right: 18px; top: 3px; z-index: 99"><i class="fa fa-print"></i> Imprimir</a>
				<a href="/medic/appointments/{{ $appointment->id }}/pdf" class="btn btn-default" style="position: absolute; right: 113px; top: 3px; z-index: 99"  target="_blank">PDF</a>
		    	<summary-appointment :history="{{ $history }}" :medicines="{{ $patient->medicines()->where('medic_id', auth()->id())->get() }}" :notes="{{ $appointment->diseaseNotes }}" :exams="{{ $appointment->physicalExams }}" :diagnostics="{{ $appointment->diagnostics }}" :treatments="{{ $appointment->treatments }}" instructions="{{ $appointment->medical_instructions }}" :labexams="{{ $appointment->labexams }}" :is-current="true"></summary-appointment>
		      
		    </div>
		</div>
				
	</section>
	
@endsection
@section('scripts')
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/moment/locale/es.js"></script>
<script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script src="/js/plugins/iCheck/icheck.min.js"></script>
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="/js/plugins/ajaxupload.js"></script>
<script>
  $(function () {
		$('#datetimepickerLabResult').datetimepicker({
			format:'YYYY-MM-DD',
            locale: 'es',
            
         });
		 $('#datetimepickerLabExam').datetimepicker({
			format:'YYYY-MM-DD',
            locale: 'es',
            
         });
  	$("#UploadFile").ajaxUpload({
      url : "/medic/patients/files",
      name: "file",
      data: {patient_id: {{ $appointment->patient->id }}, appointment_id: {{ $appointment->id }} },
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
			
		       
          var li = "<li><a href='/storage/"+ result.file +"'' title='"+ result.file.split("/")[3]+ "' target='_blank'><span class='text'>"+ result.file.split("/")[3] +"</span></a>"+
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

        $.post(url,{file: btn_delete.attr("data-file"), id: btn_delete.attr("data-id") }, function(data){
            btn_delete.parents('li').fadeOut("slow");
        });
    }


    $('.btn-finish-appointment').on('click', function (e) {
        $.ajax({
				type: 'PUT',
				url: '/medic/appointments/{{ $appointment->id }}/finished',
				data: {},
				success: function (resp) {
					
					console.log('cita finalizada');
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
						
						window.location = '/medic/appointments/create?clinic={{ $appointment->office_id }}&p={{ $appointment->patient_id }}';
								
								
					}, function (dismiss) {
						// dismiss can be 'cancel', 'overlay',
						// 'close', and 'timer'
						if (dismiss === 'cancel') {
	
						 window.location = '/medic/appointments?clinic={{ $appointment->office_id }}';
								
						}else{
							window.location = '/medic/appointments/{{ $appointment->id }}/edit';
						}
					})
					
				},
				error: function () {
					console.log('error finalizando citan');

				}

			});
				
        
    });
  
  });
</script>
@endsection
