@extends('layouts.app-pharmacy')
@section('css')
 <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
<link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 
@endsection
@section('content')
	<div id="infoBox" class="alert"></div> 
	@include('layouts/partials/header-pages',['page'=>'Pacientes'])
	<div class="row">
		<div class="col-md-8">
			 <a href="{{ url('/pharmacy/patients/'.$patient->id .'/appointments/create') }}" class="btn btn-success" style="margin-left: 15px;margin-top: 5px;">Iniciar consulta farmaceutica</a>
		</div>
	</div>
	<section class="content">
      
      <div class="row">
        <div class="col-md-4">
			
          @include('medic/patients/partials/photo', ['patient' => $patient, 'read' =>'true'])
          
        </div>
		 
		<div class="col-md-8">
			<div class="nav-tabs-custom">
	            <ul class="nav nav-tabs">
	              <li class="active"><a href="#basic" data-toggle="tab">Información Básica</a></li>
	        
	              <li class=""><a href="#pressure" data-toggle="tab">Control de presión</a></li>
	              <li class=""><a href="#sugar" data-toggle="tab">Control de azúcar</a></li>
	              <li class=""><a href="#medicines" data-toggle="tab">Medicamentos</a></li>
 				  
	             
	              
	            </ul>
	            <div class="tab-content">
	              	<div class="active tab-pane" id="basic">
						<form method="POST" action="{{ url('/pharmacy/patients/'.$patient->id) }}" class="form-horizontal">
					         {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
					         @include('medic/patients/partials/form',['buttonText' => 'Actualizar Paciente', 'read'=>'true'])
					    </form>

					</div>
					<div class="{{ isset($tab) ? ($tab =='pressure') ? 'active' : '' : 'active' }} tab-pane" id="pressure">
						
						<pharmacy-pressure-control :pressures="{{ $patient->ppressures()->orderBy('created_at','DESC')->get() }}" :patient="{{ $patient }}" today="{{ Carbon\Carbon::now()->toDateString() }}" time="{{ Carbon\Carbon::now()->toTimeString() }}"></pharmacy-pressure-control>	

				    </div>
				    <!-- /.tab-pane -->
				    <div class="{{ isset($tab) ? ($tab =='sugar') ? 'active' : '' : '' }} tab-pane" id="sugar">
					   <pharmacy-sugar-control :sugars="{{ $patient->psugars()->orderBy('created_at','DESC')->get() }}" :patient="{{ $patient }}" today="{{ Carbon\Carbon::now()->toDateString() }}" time="{{ Carbon\Carbon::now()->toTimeString() }}"></pharmacy-sugar-control>	
				    </div>
				    <!-- /.tab-pane -->
				    <div class="{{ isset($tab) ? ($tab =='medicines') ? 'active' : '' : '' }} tab-pane" id="medicines">
						
					     <div class="box box-info">

							    <div class="box-header with-border">
							      <h3 class="box-title">Medicamentos</h3>

							      <div class="box-tools pull-right">
							        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							        </button>
							      </div>
							      <!-- /.box-tools -->
							    </div>
							    <!-- /.box-header -->
							    <div class="box-body">
							     
							       
									<pharmacy-medicines :medicines="{{ $patient->pmedicines }}" :patient="{{ $patient }}" today="{{ Carbon\Carbon::now()->toDateString() }}"></pharmacy-medicines>	
							      
							        
							    </div>
							    <!-- /.box-body -->
							</div>
					    
				    </div>
				   
				 
				    

				              
				             
	            </div>
	            <!-- /.tab-content -->
	        </div>
	          <!-- /.nav-tabs-custom -->
			
		</div>

	  </div>
	</section>

	

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
<script src="{{ elixir('/js/pharmacy.patients.min.js') }}"></script>
<script>
  $(function () {
	
	$('#datetimepickerLabResult').datetimepicker({
			format:'YYYY-MM-DD',
            locale: 'es',
            
		 });
		 
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
      url : "/pharmacy/patients/files",
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
            url = "/pharmacy/patients/files/delete";

        $.post(url,{file: btn_delete.attr("data-file") }, function(data){
            btn_delete.parents('li').fadeOut("slow");
        });
    }



  });
</script>
@endsection

