@extends('layouts.app-admin')
@section('css')

@endsection
@section('content')
	
	@include('layouts/partials/header-pages',['page'=>'Editar Usuario'])
	
	<section class="content">
      
      <div class="row">
        
		 
		<div class="col-md-6">
			<div class="nav-tabs-custom">
	            <ul class="nav nav-tabs">
	              <li class="active"><a href="#basic" data-toggle="tab">Información Básica</a></li>
	             
	              
	            </ul>
	            <div class="tab-content">
	              	<div class="active tab-pane" id="basic">
						<form method="POST" action="{{ url('/admin/users/'.$user->id) }}" class="form-horizontal">
					         {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
					         @include('admin/users/partials/form',['buttonText' => 'Actualizar Usuario'])
					    </form>

				    </div>
				    <!-- /.tab-pane -->
				      
				             
	            </div>
	            <!-- /.tab-content -->
	        </div>
	          <!-- /.nav-tabs-custom -->
			
			
		</div>
		<div class="col-md-6">
		
	              		 @if($user->hasRole('medico'))
						  <div class="box box-solid box-medics">
							<div class="box-header with-border">
								<h4 class="box-title">Subscripcion Actual @if(!$subscription)(No tiene Subscripción)@endif</h4>
								
							</div>
							<div class="box-body">
								@if($subscription)
								<form method="POST" action="{{ url('/admin/users/'.$user->id.'/subscriptions') }}" class="form-horizontal">
									{{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
									<div class="form-group">
										<label for="plan" class="col-sm-2 control-label">Subscripcion</label>

										<div class="col-sm-10">
										<select class="form-control select2" style="width: 100%;" name="plan_id" required>
											
											@foreach($plans as $plan)
												<option {{ ($plan->id == $user->subscription->plan_id) ? 'selected' : '' }} value="{{ $plan->id }}" >{{ $plan->title }}</option>
											@endforeach
											
										</select>
										
										@if ($errors->has('plan_id'))
											<span class="help-block">
												<strong>{{ $errors->first('plan_id') }}</strong>
											</span>
										@endif
										</div>
									</div>
										
										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-success">Actualizar</button>
											<button type="submit" class="btn btn-danger" form="form-delete-subscription" formaction="{!! url('/admin/users/'.$user->id.'/subscriptions') !!}">Cancelar Subscripción</button>
								
							
											</div>
										</div>
										
								</form>
								@else 
									<form method="POST" action="{{ url('/admin/users/'.$user->id.'/subscriptions') }}" class="form-horizontal">
									{{ csrf_field() }}
									<div class="form-group">
										<label for="plan" class="col-sm-2 control-label">Subscripción</label>

										<div class="col-sm-10">
										<select class="form-control select2" style="width: 100%;" name="plan_id" required>
											
											@foreach($plans as $plan)
												<option value="{{ $plan->id }}" >{{ $plan->title }}</option>
											@endforeach
											
										</select>
										
										@if ($errors->has('plan_id'))
											<span class="help-block">
												<strong>{{ $errors->first('plan_id') }}</strong>
											</span>
										@endif
										</div>
									</div>
										
										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
											<button type="submit" class="btn btn-success">Agregar Subscription</button>
											
							
											</div>
										</div>
										
								</form>
								@endif
							</div>
							<!-- /.box-body -->
						</div>
						@endif

				   
	       
			
			
		</div>

	  </div>
	</section>

<form method="post" id="form-delete-subscription" data-confirm="Estas Seguro?">
  <input name="_method" type="hidden" value="DELETE">{{ csrf_field() }}
</form>

@endsection
@section('scripts')

@endsection

