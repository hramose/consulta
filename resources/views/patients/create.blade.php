@extends('layouts.app')

@section('content')

	  @include('layouts/partials/header-pages',['page'=>'Pacientes'])
	<section class="content">
      
      <div class="row">
        <div class="col-md-4">
			
          @include('patients/partials/photo')
         
          
         
        </div>
		 
		<div class="col-md-8">
			<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#basic" data-toggle="tab">Información Básica</a></li>
              
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="basic">
	                <form method="POST" action="{{ url('/medic/patients') }}" class="form-horizontal">
      				          
                            {{ csrf_field() }}
          				            @include('patients/partials/form')
                           
                           
                       
      			    	</form>
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
<script src="/js/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/js/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script>
  $(function () {
  
    $("[data-mask]").inputmask();
  });
</script>
@endsection
