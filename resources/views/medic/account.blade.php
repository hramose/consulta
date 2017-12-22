@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
   <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
   <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
   <link rel="stylesheet" href="/js/plugins/hopscotch/css/hopscotch.min.css">  
@endsection
@section('content')
    
    <div id="infoBox" class="alert alert-success" ></div>
  @include('layouts/partials/header-pages',['page'=>'Perfil'])


    <section class="content">
       <div class="row">
       <div class="col-md-12">
        <div class="panel">
          <div class="panel-body">
          @include('layouts/partials/buttons-agenda-clinic')
          
          </div>
         
        </div>
         
        </div>
       </div>
      <div class="row">
        <div class="col-md-4">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <!--<img class="profile-user-img img-responsive img-circle" src="/img/user4-128x128.jpg" alt="User profile picture">-->
             
                  <img class="profile-user-img img-responsive img-circle" src="{{ getAvatar(auth()->user()) }}" alt="User profile picture">
              
  
              
              <h3 class="profile-username text-center">{{ $user->name }}</h3>

              <p class="text-muted text-center">
                 
                  @if($user->specialities->count())
                    @foreach($user->specialities as $speciality) {{ $speciality->name }} @endforeach
                  @else
                    Médico General
                  @endif
              </p>

               <a class="UploadButton btn btn-primary btn-block" id="UploadPhoto" data-url="/medic/account/avatars">Subir Foto</a>
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
            <ul class="nav nav-tabs" id="tabs-profile">
              <li class="{{ isset($tab) ? ($tab =='profile') ? 'active' : '' : 'active' }}"><a href="#profile" data-toggle="tab" class="tab-profile">Perfil</a></li>
              <li class="{{ isset($tab) ? ($tab =='clinics') ? 'active' : '' : '' }}"><a href="#clinics" data-toggle="tab" class="tab-consultorios">Consultorios</a></li>
              <li class="{{ isset($tab) ? ($tab =='assistant') ? 'active' : '' : '' }}"><a href="#assistant" data-toggle="tab">Asistentes</a></li>
              <li class="{{ isset($tab) ? ($tab =='reviews') ? 'active' : '' : '' }}"><a href="#reviews" data-toggle="tab">Comentarios</a></li>
            </ul>
            <div class="tab-content">
              <div class="{{ isset($tab) ? ($tab =='profile') ? 'active' : '' : 'active' }} tab-pane" id="profile">
                 <form method="POST" action="{{ url('/medic/account/edit') }}" class="form-horizontal">
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
                    <label for="phone" class="col-sm-2 control-label">Teléfono</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="phone" name="phone" placeholder="Teléfono de contacto" value="{{ old('phone') ?: $user->phone }}" required>
                       @if ($errors->has('phone'))
                          <span class="help-block">
                              <strong>{{ $errors->first('phone') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                   <div class="form-group">
                    <label for="medic_code" class="col-sm-2 control-label">Código de Médico</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="medic_code" name="medic_code" placeholder="Código de Médico" value="{{ old('medic_code') ?: $user->medic_code }}" required>
                       @if ($errors->has('medic_code'))
                          <span class="help-block">
                              <strong>{{ $errors->first('medic_code') }}</strong>
                          </span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="speciality_id" class="col-sm-2 control-label">Especialidad</label>

                    <div class="col-sm-10">
                      <select class="form-control select2" style="width: 100%;" name="speciality[]" placeholder="-- Selecciona Especialidad --" multiple disabled>
                        
                        @foreach($specialities as $speciality)
                            <option value="{{$speciality->id}}" @foreach($user->specialities as $s) @if($speciality->id == $s->id)selected="selected"@endif @endforeach>{{$speciality->name}}</option>
                        @endforeach
                       
                      </select>
                    </div>
                  </div>
                   <div class="form-group">
                   <label for="minTime" class="col-sm-2 control-label">Horario de atención</label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control"  name="minTime" id="timepicker1" value="{{ old('minTime') ?: $user->settings->minTime }}">

                        <div class="input-group-addon">
                          <i class="fa fa-clock-o"></i>
                        </div>

                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="input-group">
                        <input type="text" class="form-control"  name="maxTime" id="timepicker2" value="{{ old('maxTime') ?: $user->settings->maxTime }}">

                        <div class="input-group-addon">
                          <i class="fa fa-clock-o"></i>
                        </div>
                      </div>
                      
                    </div>
                    
                  </div> 
                  <div class="form-group">
                     <label for="free_days" class="col-sm-2 control-label">Dias Libres</label>
                    <div class="col-sm-4">
                   
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="freeDays[]" value="1" @foreach($user->settings->freeDays() as $d) @if("1" == $d) checked="checked" @endif @endforeach >
                              Lunes
                            </label>
                          </div>

                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="freeDays[]" value="2" @foreach($user->settings->freeDays() as $d) @if("2" == $d) checked="checked" @endif @endforeach >
                              Martes
                            </label>
                          </div>

                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="freeDays[]" value="3" @foreach($user->settings->freeDays() as $d) @if("3" == $d) checked="checked" @endif @endforeach >
                              Miércoles
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="freeDays[]" value="4" @foreach($user->settings->freeDays() as $d) @if("4" == $d) checked="checked" @endif @endforeach >
                              Jueves
                            </label>
                          </div>
                      </div>
                      <div class="col-sm-4">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="freeDays[]" value="5" @foreach($user->settings->freeDays() as $d) @if("5" == $d) checked="checked" @endif @endforeach >
                              Viernes
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="freeDays[]" value="6" @foreach($user->settings->freeDays() as $d) @if("6" == $d) checked="checked" @endif @endforeach >
                              Sabado
                            </label>
                          </div>
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" name="freeDays[]" value="0" @foreach($user->settings->freeDays() as $d) @if("0" == $d) checked="checked" @endif @endforeach >
                              Domingo
                            </label>
                          </div>
                    </div>
                </div>
                 
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="{{ isset($tab) ? ($tab =='assistant') ? 'active' : '' : '' }} tab-pane" id="assistant">
                 
                    <assistant-form :clinics="{{ $user->offices()->where('type','Consultorio Independiente')->get() }}"></assistant-form>
                    <h3>Tus Asistentes registrados</h3> 
                     <assistant-list :assistants="{{ $assistants }}"></assistant-list>
                 
                   
              </div>
              <!-- /.tab-pane -->
              <div class="{{ isset($tab) ? ($tab =='clinics') ? 'active' : '' : '' }} tab-pane" id="clinics">
                  <div class="callout callout-info">
                    <h4>Informacion importante!</h4>

                    <p>Agrega los consultorios donde brindarás consulta privada. Si el nombre de la <b>"Clínica privada"</b> no aparece, puedes solicitar integrarla al sistema. Nos pondremos en contacto con el administrador para crear la clínica lo antes posible.</p>
                  </div>
                  
                   <office :offices="{{ $user->offices }}"></office>
              </div>
              <!-- /.tab-pane -->
               <div class="{{ isset($tab) ? ($tab =='reviews') ? 'active' : '' : '' }} tab-pane" id="reviews">
                   <div class="general-review">
                   <h2>Nivel de satisfacción en general</h2>
                   <div class="ratings">
                      
                     

                          @if(1 <= ($user->rating_service_cache + $user->rating_medic_cache)/2 && ($user->rating_service_cache + $user->rating_medic_cache)/2 < 2)
                            <img src="/img/muy-malo.png" alt="1" title="Muy Malo">
                          @else
                            <img src="/img/muy-malo-off.png" alt="1" title="Muy Malo">
                          @endif
                          @if(2 <= ($user->rating_service_cache + $user->rating_medic_cache)/2 && ($user->rating_service_cache + $user->rating_medic_cache)/2 < 3)
                           <img src="/img/malo.png" alt="2" title="Malo">
                          @else
                            <img src="/img/malo-off.png" alt="2" title="Malo">
                          @endif
                          @if(3 <= ($user->rating_service_cache + $user->rating_medic_cache)/2 && ($user->rating_service_cache + $user->rating_medic_cache)/2 < 4)
                           <img src="/img/regular.png" alt="3" title="regular">
                          @else
                            <img src="/img/regular-off.png" alt="3" title="regular">
                          @endif
                          @if(4 <= ($user->rating_service_cache + $user->rating_medic_cache)/2 && ($user->rating_service_cache + $user->rating_medic_cache)/2 < 5)
                           <img src="/img/bueno.png" alt="4" title="Bueno">
                          @else
                            <img src="/img/bueno-off.png" alt="4" title="Bueno">
                          @endif
                          @if(5 <= ($user->rating_service_cache + $user->rating_medic_cache)/2)
                           <img src="/img/excelente.png" alt="5" title="Excelente">
                          @else
                            <img src="/img/excelente-off.png" alt="5" title="Excelente">
                          @endif
                       
                     

                      </div>
                      <div class="ratings-targets">{!! number_format(($user->rating_service_cache + $user->rating_medic_cache)/2, 1) !!} Puntos</div>
                  </div>
                  <h3>Nivel de satisfación del  servicio recibido</h3>
                                   
                      <!-- @for ($i=1; $i <= 5 ; $i++)
                          <span class="fa fa-star{!! ($i <= $user->rating_service_cache) ? '' : '-o'!!}"></span>
                      @endfor -->
                      <div class="ratings">
                      
                     

                          @if(1 <= $user->rating_service_cache && $user->rating_service_cache < 2)
                            <img src="/img/muy-malo.png" alt="1" title="Muy Malo">
                          @else
                            <img src="/img/muy-malo-off.png" alt="1" title="Muy Malo">
                          @endif
                          @if(2 <= $user->rating_service_cache && $user->rating_service_cache < 3)
                           <img src="/img/malo.png" alt="2" title="Malo">
                          @else
                            <img src="/img/malo-off.png" alt="2" title="Malo">
                          @endif
                          @if(3 <= $user->rating_service_cache && $user->rating_service_cache < 4)
                           <img src="/img/regular.png" alt="3" title="regular">
                          @else
                            <img src="/img/regular-off.png" alt="3" title="regular">
                          @endif
                          @if(4 <= $user->rating_service_cache && $user->rating_service_cache < 5)
                           <img src="/img/bueno.png" alt="4" title="Bueno">
                          @else
                            <img src="/img/bueno-off.png" alt="4" title="Bueno">
                          @endif
                          @if(5 <= $user->rating_service_cache)
                           <img src="/img/excelente.png" alt="5" title="Excelente">
                          @else
                            <img src="/img/excelente-off.png" alt="5" title="Excelente">
                          @endif
                       
                     

                      </div>
                      <div class="ratings-targets">{!! number_format($user->rating_service_cache, 1) !!} Puntos</div>
                    
                 <div class="box box-success box-comments">
                  <div class="box-header">
                    <i class="fa fa-comments-o"></i>

                    <h3 class="box-title">Comentarios</h3>

                    <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                      Total: {!! $user->rating_service_count !!}
                      <!-- <div class="btn-group" data-toggle="btn-toggle">
                        <button type="button" class="btn btn-default btn-sm active"><i class="fa fa-square text-green"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i></button>
                      </div> -->
                    </div>
                  </div>
                 <div class="box-body chat comments-box">
                  
                  @foreach($reviewsS = $user->reviewsService()->orderBy('created_at','DESC')->paginate(5) as $review)
                    <!-- chat item -->
                    <div class="item">
                      <img src="/img/user3-128x128.jpg" alt="user image" class="offline">

                      <p class="message">
                        <a href="#" class="name">
                          <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{ $review->created_at }}</small>
                          {{ $review->rating }} Puntos
                        </a>
                        {{ $review->comment }}
                      </p>
                    </div>
                    <!-- /.item -->
                     @endforeach
                    
                  </div>
                  <div class="box-footer">
                     {{ $reviewsS->appends(['tab' => 'reviews'])->render() }}
                  </div>
                </div>
                 
                  
                   <h3>Nivel de satisfacción con el desempeño del médico</h3>
                   
                   <div class="ratings">
                      
                     

                          @if(1 <= $user->rating_medic_cache && $user->rating_medic_cache < 2)
                            <img src="/img/muy-malo.png" alt="1" title="Muy Malo">
                          @else
                            <img src="/img/muy-malo-off.png" alt="1" title="Muy Malo">
                          @endif
                          @if(2 <= $user->rating_medic_cache && $user->rating_medic_cache < 3)
                           <img src="/img/malo.png" alt="2" title="Malo">
                          @else
                            <img src="/img/malo-off.png" alt="2" title="Malo">
                          @endif
                          @if(3 <= $user->rating_medic_cache && $user->rating_medic_cache < 4)
                           <img src="/img/regular.png" alt="3" title="regular">
                          @else
                            <img src="/img/regular-off.png" alt="3" title="regular">
                          @endif
                          @if(4 <= $user->rating_medic_cache && $user->rating_medic_cache < 5)
                           <img src="/img/bueno.png" alt="4" title="Bueno">
                          @else
                            <img src="/img/bueno-off.png" alt="4" title="Bueno">
                          @endif
                          @if(5 <= $user->rating_medic_cache)
                           <img src="/img/excelente.png" alt="5" title="Excelente">
                          @else
                            <img src="/img/excelente-off.png" alt="5" title="Excelente">
                          @endif
                       
                     

                      </div>
                      <div class="ratings-targets">{!! number_format($user->rating_medic_cache, 1) !!} Puntos</div>
                      <div class="box box-success box-comments">
                        <div class="box-header">
                          <i class="fa fa-comments-o"></i>

                          <h3 class="box-title">Comentarios</h3>

                          <div class="box-tools pull-right" data-toggle="tooltip" title="Status">
                            Total: {!! $user->rating_service_count !!}
                            <!-- <div class="btn-group" data-toggle="btn-toggle">
                              <button type="button" class="btn btn-default btn-sm active"><i class="fa fa-square text-green"></i>
                              </button>
                              <button type="button" class="btn btn-default btn-sm"><i class="fa fa-square text-red"></i></button>
                            </div> -->
                          </div>
                        </div>
                       <div class="box-body chat comments-box">
                        
                        @foreach($reviewsM = $user->reviewsMedic()->orderBy('created_at','DESC')->paginate(5) as $review)
                          <!-- chat item -->
                          <div class="item">
                            <img src="/img/user3-128x128.jpg" alt="user image" class="offline">

                            <p class="message">
                              <a href="#" class="name">
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{ $review->created_at }}</small>
                                {{ $review->rating }} Puntos
                              </a>
                              {{ $review->comment }}
                            </p>
                          </div>
                          <!-- /.item -->
                           @endforeach
                          
                        </div>
                         <div class="box-footer">
                           {{ $reviewsM->appends(['tab' => 'reviews'])->render() }}
                        </div>
                      </div>
                  
                   
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
<script src="/js/plugins/moment/moment.min.js"></script>
<script src="/js/plugins/moment/locale/es.js"></script>
<!-- <script src="/js/plugins/fullcalendar/locale/es.js"></script> -->
<script src="/js/bootstrap.min.js"></script>

 <script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
  <script src="/js/plugins/hopscotch/js/hopscotch.min.js"></script> 
<script>
  $(function () {
     $('.comments-box').slimScroll({
      height: '250px'
    });
     $(".dropdown-toggle").dropdown();
    //Initialize Select2 Elements
    $('#datetimepicker1').datetimepicker({
            format:'YYYY-MM-DD',
            locale: 'es',
            
         });
     $('#datetimepicker2').datetimepicker({
                          format: 'HH:mm',
                          stepping: 30,
                          //useCurrent: false
                          
     });
     $('#timepicker1').datetimepicker({
                          format: 'HH:mm',
                          stepping: 30,
                          //useCurrent: false
                          
     });
     $('#timepicker2').datetimepicker({
                          format: 'HH:mm',
                          stepping: 30,
                          //useCurrent: false
                          
     });
    /*$('#modalOfficeNotification').find('.btn-save-notification').on('click',function (e) {
      e.preventDefault();
      var office_id = $(this).attr('data-office');
      if(office_id)
      {
        $.ajax({
              type: 'PUT',
              url: '/medic/account/offices/'+ office_id + '/notification',
              data: { notification: 1, notification_date: $('#modalOfficeNotification').find('#datetimepicker1').val() },
              success: function (resp) {
                
               console.log('Notificacion actualizada')
              },
              error: function () {
                console.log('error updating Notificacion');

              }
          });
      }

    });*/

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
<script>
 

@if(!auth()->user()->active && !$userOffices)
  var tour = {
      id: "inactive-user-consultorios",
      
      i18n: {
          nextBtn: "Siguiente",
          prevBtn: "Atras",
          doneBtn: "Listo"
        },
       
      steps: [
        {
          title: "Cuenta esta inactiva",
          content: "Esta cuenta esta inactiva mientras el administrador verifica tus datos. Puedes seguir editando tus opciones mientras se activa. <a class='popup-youtube' href='http://www.youtube.com/watch?v=DrYMxb-7WQI'>EMPIECE AQUI!</a>",
          target: "#tabs-profile .tab-profile",
          placement: "top",
         
              
        },
        {
          title: "Consultorios",
          content: "Recuerda agregar tus consultorios o clinica para poder ser agregado en el catalogo de busquedas!",
          target: "#tabs-profile .tab-consultorios",
          placement: "top",
          
          
          
        }
        
      ],
      onEnd: function () {
       
       // localStorage.setItem("tour_viewed", 1)

      }

    };

  
    hopscotch.startTour(tour);
   
   
    
@elseif(!auth()->user()->active)

    var tour = {
      id: "inactive-user",
      
      i18n: {
          nextBtn: "Siguiente",
          prevBtn: "Atras",
          doneBtn: "Listo"
        },
       
      steps: [
        {
          title: "Cuenta esta inactiva",
          content: "Esta cuenta esta inactiva mientras el administrador verifica tus datos. Puedes seguir editando tus opciones mientras se activa. <a class='popup-youtube' href='http://www.youtube.com/watch?v=DrYMxb-7WQI'>EMPIECE AQUI!</a>",
          target: "#tabs-profile .tab-profile",
          placement: "top",
         
          
        }
       
        
      ],
      onEnd: function () {
       
       // localStorage.setItem("tour_viewed", 1)

      }

    };

  
    hopscotch.startTour(tour);

@elseif(!$userOffices)
     var tour = {
      id: "consultorios-user",
      
      i18n: {
          nextBtn: "Siguiente",
          prevBtn: "Atras",
          doneBtn: "Listo"
        },
       
      steps: [
       {
          title: "Consultorios",
          content: "Recuerda agregar tus consultorios o clinica para poder ser agregado en el catalogo de busquedas!",
          target: "#tabs-profile .tab-consultorios",
          placement: "top",
         
          
        }
       
        
      ],
      onEnd: function () {
       
       // localStorage.setItem("tour_viewed", 1)

      }

    };

  
    hopscotch.startTour(tour);

@endif
 
</script>
@endsection
