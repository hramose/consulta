@extends('layouts.app-patient')
@section('css')
<link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
   <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css"> 
@endsection
@section('content')
	<div id="infoBox" class="alert"></div> 
	@include('layouts/partials/header-pages',['page'=>'Expediente del paciente'])
	
	<section class="content">
      <div class="row">
		<div class="col-md-12">
			<form action="/">
				<div class="form-group">
					<select name="patient" id="" class="form-control">
						@foreach(auth()->user()->patients as $p)
							<option value="{{ $p->id}}" {{ ($patient->id == $p->id) ? 'selected': ''}} >{{ $p->fullname }}</option>
						@endforeach
					</select>
					
				</div>
			</form>
			
		</div>
	</div>
      <div class="row">
       
		 
		<div class="col-md-7">
			<h3>Mi control personal</h3>
			<div class="nav-tabs-custom">
	            <ul class="nav nav-tabs">
	              <li class="{{ isset($tab) ? ($tab =='pressure') ? 'active' : '' : 'active' }}"><a href="#pressure" data-toggle="tab">Control de presión</a></li>
	              <li class="{{ isset($tab) ? ($tab =='sugar') ? 'active' : '' : '' }}"><a href="#sugar" data-toggle="tab">Control de azúcar</a></li>
	              <li class="{{ isset($tab) ? ($tab =='medicines') ? 'active' : '' : '' }}"><a href="#medicines" data-toggle="tab">Mis medicamentos</a></li>
 				  <li class="{{ isset($tab) ? ($tab =='alergies') ? 'active' : '' : '' }}"><a href="#alergies" data-toggle="tab">Soy alergico a:</a></li>
 				  
	              
	            </ul>
	            <div class="tab-content">
	              	<div class="{{ isset($tab) ? ($tab =='pressure') ? 'active' : '' : 'active' }} tab-pane" id="pressure">
						
						<pressure-control :pressures="{{ $patient->pressures }}" :patient_id="{{ $patient->id }}"></pressure-control>	

				    </div>
				    <!-- /.tab-pane -->
				    <div class="{{ isset($tab) ? ($tab =='sugar') ? 'active' : '' : '' }} tab-pane" id="sugar">
					   <sugar-control :sugars="{{ $patient->sugars }}" :patient_id="{{ $patient->id }}"></sugar-control>	
				    </div>
				    <!-- /.tab-pane -->
				    <div class="{{ isset($tab) ? ($tab =='medicines') ? 'active' : '' : '' }} tab-pane" id="medicines">
						
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
							     
							       
									<medicines :medicines="{{ $patient->medicines }}" :patient_id="{{ $patient->id }}" url="/account/patients"></medicines>	
							      
							        
							    </div>
							    <!-- /.box-body -->
							</div>
					    
				    </div>
				    <!-- /.tab-pane -->
				    <div class="{{ isset($tab) ? ($tab =='alergies') ? 'active' : '' : '' }} tab-pane" id="alergies">
					   		 <div class="box box-info">

							    <div class="box-header with-border">
							      <h3 class="box-title">Alergias</h3>

							      <div class="box-tools pull-right">
							        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							        </button>
							      </div>
							      <!-- /.box-tools -->
							    </div>
							    <!-- /.box-header -->
							    <div class="box-body">
							     
							       
									<allergies :allergies="{{ $patient->history->allergies->load('user.roles') }}" :patient_id="{{ $patient->id }}"></allergies>	
							      
							        
							    </div>
							    <!-- /.box-body -->
							</div>
				    </div>
				    
				    

				              
				             
	            </div>
	            <!-- /.tab-content -->
	        </div>
	          <!-- /.nav-tabs-custom -->
			 
		</div>
		<div class="col-md-5">
					<h3>Control Médico</h3>
					<div class="nav-tabs-custom">
			            <ul class="nav nav-tabs">
			              <li class="{{ isset($tab) ? ($tab =='history') ? 'active' : '' : 'active' }}"><a href="#history" data-toggle="tab">Historial Médico</a></li>
			              <li class="{{ isset($tab) ? ($tab =='appointments') ? 'active' : '' : '' }}"><a href="#appointments" data-toggle="tab">Historial Consultas</a></li>
			              
		 				  
			              
			            </ul>
			            <div class="tab-content">
			              	<div class="{{ isset($tab) ? ($tab =='history') ? 'active' : '' : 'active' }} tab-pane" id="history">
								
								 <history :history="{{ $patient->history }}" :appointments="{{ $patient->appointments->load('user','diagnostics') }}" :read="true"></history>

						    </div>
						    <!-- /.tab-pane -->
						    <div class="{{ isset($tab) ? ($tab =='appointments') ? 'active' : '' : '' }} tab-pane" id="appointments">
							   	<ul class="products-list product-list-in-box">
			                       @foreach($patient->appointments()->where('status', 1)->limit(5)->get() as $appointment)
			                          <li class="item">
			                            <div class="product-img">
			                              
			                                 <img class="profile-user-img img-responsive img-circle" src="{{ getAvatar($appointment->patient) }}" alt="User profile picture">
			                            </div>
			                            <div class="product-info">
			                              <a href="{{ url('/appointments/'.$appointment->id.'/show') }}" class="product-title"> {{ $appointment->title }}
			                                <span class="label label-warning pull-right">{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}</span></a>
			                                  <span class="product-description">
			                                   {{ $appointment->patient->first_name }} <span class="label label-success">{{ \Carbon\Carbon::parse($appointment->start)->format('h:i:s A') }} a {{ \Carbon\Carbon::parse($appointment->end)->format('h:i:s A') }}</span>
			                                  </span>
			                            </div>
			                          </li>
			                       @endforeach
			                    </ul>
			                    <div>
			                    	 <p><a href="/appointments">Ver más consultas</a></p>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/locale/es.js"></script>
<!-- <script src="/js/plugins/fullcalendar/locale/es.js"></script> -->
<script src="/js/bootstrap.min.js"></script>

 <script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script>
  $(function () {
   
      $('select[name="patient"]').on('change',function (e) {
        e.preventDefault();
  
       // console.log($(this).val());
       window.location.href = "/expedients/"+ $(this).val()+'/show';
       
     });

     //$('select[name="patient"]').val();

     $(".dropdown-toggle").dropdown();
    //Initialize Select2 Elements
    $('#datetimepicker1').datetimepicker({
            format:'YYYY-MM-DD',
            locale: 'es',
            //useCurrent: true,
            //defaultDate: new Date(),
         });
     
     $('#datetimepicker2').datetimepicker({
                          format: 'HH:mm',
                          stepping: 30,
                          //useCurrent: true
                          
     });
      $('#datetimepicker3').datetimepicker({
            format:'YYYY-MM-DD',
            locale: 'es',
            //useCurrent: true,
            //defaultDate: new Date(),
         });
     
     $('#datetimepicker4').datetimepicker({
                          format: 'HH:mm',
                          stepping: 30,
                          //useCurrent: true
                          
     });
    
    
  });
</script>
@endsection

