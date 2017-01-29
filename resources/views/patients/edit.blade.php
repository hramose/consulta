@extends('layouts.app')

@section('content')
	<div id="infoBox" class="alert"></div> 
	@include('layouts/partials/header-pages',['page'=>'Pacientes'])
	<div class="row">
		<div class="col-md-8">
			<a href="{{ url('/medic/appointments/create?p='.$patient->id) }}" class="btn btn-success" style="margin-left: 15px;margin-top: 5px;">Crear cita a este paciente</a>
		</div>
	</div>
	<section class="content">
      
      <div class="row">
        <div class="col-md-4">
			
          @include('patients/partials/photo', ['patient' => $patient])
          @include('patients/partials/signs', ['patient' => $patient])
          @include('patients/partials/files', ['files' => $files])
          
         
        </div>
		 
		<div class="col-md-8">
			<div class="nav-tabs-custom">
	            <ul class="nav nav-tabs">
	              <li class="active"><a href="#basic" data-toggle="tab">Información Básica</a></li>
	              <li><a href="#history" data-toggle="tab">Historial Médico</a></li>
	              <li><a href="#appointments" data-toggle="tab">Consultas</a></li>
	             
	              
	            </ul>
	            <div class="tab-content">
	              	<div class="active tab-pane" id="basic">
						<form method="POST" action="{{ url('/medic/patients/'.$patient->id) }}" class="form-horizontal">
					         {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
					         @include('patients/partials/form',['buttonText' => 'Actualizar Paciente'])
					    </form>

				    </div>
				    <!-- /.tab-pane -->
				    <div class="tab-pane" id="history">
					    <history :history="{{ $patient->history }}"></history>	
				    </div>
				    <!-- /.tab-pane -->
				    <div class="tab-pane" id="appointments">
						
					      @include('patients/partials/appointments')
					    
				    </div>
				    <!-- /.tab-pane -->
				    

				              
				             
	            </div>
	            <!-- /.tab-content -->
	        </div>
	          <!-- /.nav-tabs-custom -->
			 @include('patients/partials/medicines', ['patient' => $patient])	
		</div>

	  </div>
	</section>
@endsection
@section('scripts')
<script src="/js/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/js/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/js/plugins/ajaxupload.js"></script>
<script>
  $(function () {
  
    $("[data-mask]").inputmask();

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

