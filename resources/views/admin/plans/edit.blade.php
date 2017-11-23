@extends('layouts.app-admin')
@section('css')

@endsection
@section('content')
	
	@include('layouts/partials/header-pages',['page'=>'Editar Plan'])
	
	<section class="content">
      
      <div class="row">
        
		 
		<div class="col-md-8">
			<div class="nav-tabs-custom">
	            <ul class="nav nav-tabs">
	              <li class="active"><a href="#basic" data-toggle="tab">Información del plan</a></li>
	             
	              
	            </ul>
	            <div class="tab-content">
	              	<div class="active tab-pane" id="basic">
						<form method="POST" action="{{ url('/admin/plans/'.$plan->id) }}" class="form-horizontal">
					         {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
					         @include('admin/plans/partials/form',['buttonText' => 'Actualizar Plan'])
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

@endsection

