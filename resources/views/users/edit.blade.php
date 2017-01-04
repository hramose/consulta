@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
@endsection
@section('content')
     <div id="infoBox" class="alert"></div> 
  @include('layouts/partials/header-pages',['page'=>'Cuenta'])


    <section class="content">
      
      <div class="row">
        <div class="col-md-4">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <!--<img class="profile-user-img img-responsive img-circle" src="/img/user4-128x128.jpg" alt="User profile picture">-->
              <img class="profile-user-img img-responsive img-circle" src="{{ Storage::url('avatars/'.auth()->user()->id.'/avatar.jpg') }}" alt="User profile picture">
              
              <h3 class="profile-username text-center">{{ $user->name }}</h3>

              <p class="text-muted text-center">{{ $user->getSpecialityName() }}</p>

               <a class="UploadButton btn btn-primary btn-block" id="UploadPhoto" data-url="/account/avatars">Subir Foto</a>
               <small class="center txt-center">Medidas recomendadas (128 x 128)</small>
              
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
         
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Perfil</a></li>
              <li><a href="#timeline" data-toggle="tab">Consultorio</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                 <form method="POST" action="{{ url('/account/edit') }}" class="form-horizontal">
                    {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
                  <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Nombre</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name') ?: $user->name }}" required>
                       @if ($errors->has('name'))
                          <span class="help-block">
                              <strong>{{ $errors->first('name') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="email"  name="email" placeholder="Email"  value="{{ old('email') ?: $user->email }}" readonly required>
                      @if ($errors->has('email'))
                          <span class="help-block">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Cambiar contraseña</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="password" name="password" placeholder="Escribe la nueva contraseña">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="speciality_id" class="col-sm-2 control-label">Especialidad</label>

                    <div class="col-sm-10">
                      <select class="form-control select2" style="width: 100%;" name="speciality_id" placeholder="-- Selecciona Especialidad --">
                        <option value="0">Especialidad</option>
                        @foreach ($specialities as $speciality)
                          <option value="{{ $speciality->id }}" {{ $user->speciality_id == $speciality->id ? 'selected' : '' }}>{{ $speciality->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                 
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <form method="POST" action="{{ url('/account/office/'.$user->office->id) }}" class="form-horizontal">
                    {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
                  <div class="form-group">
                    <label for="office_name" class="col-sm-2 control-label">Nombre</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="name" placeholder="Nombre del consultorio" value="{{ old('name') ?: isset($user->office) ? $user->office->name : '' }}" required>
                       @if ($errors->has('name'))
                          <span class="help-block">
                              <strong>{{ $errors->first('name') }}</strong>
                          </span>
                      @endif
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="office_address" class="col-sm-2 control-label">Dirección</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="address" placeholder="Dirección" value="{{ old('address') ?: isset($user->office) ? $user->office->address : '' }}">
                       @if ($errors->has('address'))
                          <span class="help-block">
                              <strong>{{ $errors->first('address') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="office_province" class="col-sm-2 control-label">Provincia</label>

                    <div class="col-sm-10">
                      <select class="form-control select2" style="width: 100%;" name="province" placeholder="-- Selecciona provincia --" required>
                        <option></option>
                        <option {{ (isset($user->office)) ? $user->office->province == 'Guanacaste' ? 'selected' : '' : '' }}>Guanacaste</option>
                        <option {{ (isset($user->office)) ? $user->office->province == 'San Jose' ? 'selected' : '' : '' }}>San Jose</option>
                        <option {{ (isset($user->office)) ? $user->office->province == 'Heredia' ? 'selected' : '' : '' }}>Heredia</option>
                        <option {{ (isset($user->office)) ? $user->office->province == 'Limon' ? 'selected' : '' : '' }}>Limon</option>
                        <option {{ (isset($user->office)) ? $user->office->province == 'Cartago' ? 'selected' : '' : '' }}>Cartago</option>
                        <option {{ (isset($user->office)) ? $user->office->province == 'Puntarenas' ? 'selected' : '' : '' }}>Puntarenas</option>
                         <option {{ (isset($user->office)) ? $user->office->province == 'Alajuela' ? 'selected' : '' : '' }}>Alajuela</option>
                      </select>
                      <!--<input type="text" class="form-control" name="province" placeholder="Provincia" value="{{ old('province') ?: isset($user->office) ? $user->office->province : '' }}">-->
                       @if ($errors->has('province'))
                          <span class="help-block">
                              <strong>{{ $errors->first('province') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="office_city" class="col-sm-2 control-label">Ciudad</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="city" placeholder="Ciudad" value="{{ old('city') ?: isset($user->office) ? $user->office->city : '' }}" >
                       @if ($errors->has('city'))
                          <span class="help-block">
                              <strong>{{ $errors->first('city') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="office_phone" class="col-sm-2 control-label">Teléfono</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="phone" placeholder="Teléfono" value="{{ old('phone') ?: isset($user->office) ? $user->office->phone : '' }}">
                       @if ($errors->has('phone'))
                          <span class="help-block">
                              <strong>{{ $errors->first('phone') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                   <div class="form-group">
                    <label for="lat" class="col-sm-2 control-label">Coordenadas (Para Google Maps y Waze)</label>

                    
                         <div class="col-sm-3">
                          <div class="input-group">
                            <span class="input-group-addon">lat:</span>
                            <input type="text" class="form-control" name="lat" placeholder="10.637875" value="{{ old('lat') ?: isset($user->office) ? $user->office->lat : '' }}">
                          </div>
                           @if ($errors->has('lat'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('lat') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="col-sm-3">
                          <div class="input-group">
                            <span class="input-group-addon">lon:</span>
                            <input type="text" class="form-control" name="lon" placeholder="-85.434431" value="{{ old('lon') ?: isset($user->office) ? $user->office->lon : '' }}">
                          </div>
                           @if ($errors->has('lon'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('lon') }}</strong>
                              </span>
                          @endif
                        </div>
                        <div class="col-sm-3">
                      
                          <!-- Button trigger modal -->
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                            Ver ejemplo
                          </button>
                          

                          <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="myModalLabel">Ejemplo de Coordenadas</h4>
                                </div>
                                <div class="modal-body">
                                  <img src="/img/img-mapa-coordenadas.png" alt="Coordenadas Google Maps" style="width: 100%;" />
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>


                        </div>
                        
                      
                    
                   
                  </div>
                  <div class="form-group">
                      
                     @if(isset($user->office) && $user->office->lat && $user->office->lon)
                          <label for="lat" class="col-sm-2 control-label">Prueba</label>
                          <a href="waze://?ll={{ $user->office->lat }}, {{$user->office->lon }}&amp;navigate=yes" target="_blank" class="btn btn btn-app"><i class="fa fa-map-marker"></i> <strong>Abrir en Waze</strong></a>

                          <a href="http://maps.google.com/?saddr=Current+Location&daddr={{ $user->office->lat }}, {{$user->office->lon }}" target="_blank" class="btn btn btn-app"><i class="fa fa-map-marker"></i> <strong>Abir en Google Maps</strong></a>
                          
                          
                          @endif
                  </div>
                 
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->

             
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>


@endsection
@section('scripts')
<script src="/js/plugins/select2/select2.full.min.js"></script>
<script src="/js/plugins/ajaxupload.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    $("#UploadPhoto").ajaxUpload({
      url : $("#UploadPhoto").data('url'),
      name: "photo",
      data: {patient_id: {{ isset($patient->id) ? $patient->id : '0' }} },
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

  });
</script>
@endsection
