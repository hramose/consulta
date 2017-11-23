@extends('layouts.app-admin')

@section('content')

	  @include('layouts/partials/header-pages',['page'=>'Crear Plan'])
	<section class="content">
      
      <div class="row">
       
		<div class="col-md-8">
			<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#basic" data-toggle="tab">Información del Plan</a></li>
              
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="basic">
	                <form method="POST" action="{{ url('/admin/plans') }}" class="form-horizontal">
      				          
                            {{ csrf_field() }}
          				            @include('admin/plans/partials/form')
                           
                           
                       
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
