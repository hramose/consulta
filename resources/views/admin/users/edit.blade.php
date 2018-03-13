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

			  @if($user->hasRole('medico') && $user->fe)
						  <div class="box box-solid box-medics">
							<div class="box-header with-border">
								<h4 class="box-title">Factura Eléctronica</h4>
								
							</div>
							<div class="box-body">
								@if($configFactura)
								<form method="POST" action="{{ url('/admin/users/'.$user->id.'/configfactura') }}" class="form-horizontal" enctype="multipart/form-data">
									{{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
									@include('admin/users/partials/config-factura',['buttonText' => 'Actualizar'])
								</form>
								@else 
								 <form method="POST" action="{{ url('/admin/users/'.$user->id.'/configfactura') }}" class="form-horizontal" enctype="multipart/form-data">
									{{ csrf_field() }}
									@include('admin/users/partials/config-factura',['buttonText' => 'Guardar'])
								</form>
								@endif
								
							</div>
							<!-- /.box-body -->
						</div>
				@endif
			
			
		</div>
		<div class="col-md-6">
		
	              		 @if($user->hasRole('medico'))
						  <div class="box box-solid box-medics">
							<div class="box-header with-border">
								<h4 class="box-title">Subscripcion Actual @if(!$subscription)(No tiene Subscripción)@else <small>(Vencimiento: {{ $user->subscription->ends_at->toDateString() }})</small> @endif</h4>
								
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

						@if($user->hasRole('medico') && $configFactura)
						  <div class="box box-solid box-medics">
							<div class="box-header with-border">
								<h4 class="box-title">Prueba Factura Eléctronica (Ambiente de Pruebas Hacienda)</h4>
								
							</div>
							<div class="box-body">
								
								@if(existsCertTestFile($configFactura))

								<test-conexion-hacienda user-id="{{ $user->id }}"></test-conexion-hacienda>
								@else 
									<h3>Parece que no tiene un certificado de pruebas instalado. Agrega uno para poder realizar pruebas de conexion con Hacienda</h3>
								
								@endif
								
							</div>
							<!-- /.box-body -->
						</div>
						@endif


			@if($user->hasRole('clinica'))
			<div class="box box-solid box-medics">
				<div class="box-header with-border">
					<h4 class="box-title">Clínica</h4>
					
				</div>
				<div class="box-body">
					
				<clinic :clinic="{{ $user->offices->first() }}" :url="'/admin/users/6'"></clinic>
					
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
<form method="post" id="form-delete-configfactura" data-confirm="Estas Seguro?">
  <input name="_method" type="hidden" value="DELETE">{{ csrf_field() }}
</form>

@endsection
@section('scripts')
<script src="{{ elixir('/js/ubicaciones.min.js') }}"></script>
<script>
 $(function () {
	
       var provincias = $('#provincia'),
        cantones = $('#canton'),
		distritos =  $('#distrito');
		
		
	
    cantones.empty();
    distritos.empty();
    
	

    provincias.change(function() {
        var $this =  $(this);
        cantones.empty();
        cantones.append('<option value="">Canton</option>');
        $.each(ubicaciones, function(index,provincia) {

            if(provincia.id == $this.val()){
                $.each(provincia.cantones, function(index,canton) {

                    cantones.append('<option value="' + canton.id + '">' + canton.title + '</option>');
                });
              }
        });

    });
     cantones.change(function() {
        var $this =  $(this);
        distritos.empty();
        distritos.append('<option value="">Distrito</option>');
        $.each(ubicaciones, function(index,provincia) {
           
            if(provincia.id == provincias.val())
                $.each(provincia.cantones, function(index,canton) {
                  
                     if(canton.id == $this.val())
                     {
                      $.each(canton.distritos, function(index,distrito) {

                          distritos.append('<option value="' + distrito.id + '">' + distrito.title + '</option>');
                      });
                      
                     }
                });
        });

	});

	@if($configFactura)
	  	setTimeout(function(){

                $('#provincia option[value="{{ $configFactura->provincia }}"]').attr("selected", true);
                $('#provincia').change();
                $('#canton option[value="{{ $configFactura->canton }}"]').attr("selected", true);
				$('#canton').change();
				 $('#distrito option[value="{{ $configFactura->distrito }}"]').attr("selected", true);
            }, 100);
	@endif

});
</script>

@endsection

