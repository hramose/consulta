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
              <li class="{{ isset($tab) ? ($tab =='payments') ? 'active' : '' : '' }}"><a href="#payments" data-toggle="tab">Hitorial de pagos</a></li>
              <li class="{{ isset($tab) ? ($tab =='fe') ? 'active' : '' : '' }}"><a href="#fe" data-toggle="tab">Factura Electrónica</a></li>
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
            
               <div class="{{ isset($tab) ? ($tab =='payments') ? 'active' : '' : '' }} tab-pane" id="payments">
                 <h2>Historial de pagos</h2>
                        <div class="row">
                          <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                              <thead>
                              <tr>
                                <th>Cant.</th>
                                <th>Description</th>
                                <th>Periodo</th>
                                <th>Total</th>
                                <th>Numero de Operación</th>
                              </tr>
                              </thead>
                              <tbody>
                              @foreach($incomes as $income)
                              <tr>
                                  <td>1</td>
                                  <td>{{ $income->description }}</td>
                                  <td>{{ ($income->type == 'M') ? $income->month .'-'. $income->year : $income->period_from .' al '. $income->period_to }}</td>
                                  <td>{{ money($income->amount,'$') }}</td>
                                  <td>{{ $income->purchase_operation_number }}</td>
                              </tr>
                              @endforeach
                              </tbody>
                            </table>
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
               </div>

              <div class="{{ isset($tab) ? ($tab =='fe') ? 'active' : '' : '' }} tab-pane" id="fe">
                
                  @if($user->configFactura)
                  <div class="form-horizontal">
                    {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
                    @include('admin/users/partials/config-factura',['buttonText' => 'Actualizar','read'=> true])
                  </div>
                  @else 
                  <form method="POST" action="{{ url('/medic/account/'.$user->id.'/configfactura') }}" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @include('admin/users/partials/config-factura',['buttonText' => 'Guardar'])
                  </form>
                  @endif
               </div>
             
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
<script>
 $(function () {
	
       var provincias = $('#provincia'),
        cantones = $('#canton'),
		distritos =  $('#distrito');
		
		
	var ubicaciones = [
          {
			  title: 'San Jose',
			  id: '1',
              cantones : [
                  {
					  title: 'San Jose',
					  id: '01',
                      distritos:[
						  {id:'01', title: 'Carmen'},
						  {id:'02', title: 'Merced'},
						  {id:'03', title: 'Hospital'},
						  {id:'04', title: 'Catedral'},
						  {id:'05', title: 'Zapote'},
						  {id:'06', title: 'San Francisco De Dos Rios'},
						  {id:'07', title: 'Uruca'},
						  {id:'08', title: 'Mata Redonda'},
						  {id:'09', title: 'Pavas'},
						  {id:'10', title: 'Hatillo'},
						  {id:'11', title: 'San Sebastián'}
						]
                  },
                  {
					  title: 'Escazu',
					  id: '02',
                      distritos:[
						   {id:'01', title: 'Escazú'},
						   {id:'02', title: 'San Antonio'},
						   {id:'03', title: 'San Rafael'}
						  
						]
                  },
                  {
					  title: 'Desamparados',
					  id: '03',
                      distritos:[
						  {id:'01', title: 'Desamparados'},
						  {id:'02', title: 'San Miguel'},
						  {id:'03', title: 'San Juan De Dios'},
						  {id:'04', title: 'San Rafael Arriba'},
						  {id:'05', title: 'San Rafael Abajo'},
						  {id:'06', title: 'San Antonio'},
						  {id:'07', title: 'Frailes'},
						  {id:'08', title: 'Patarra'},
						  {id:'09', title: 'San Cristobal'},
						  {id:'10', title: 'Rosario'},
						  {id:'11', title: 'Damas'},
						  {id:'12', title: 'Gravilias'},
						  {id:'13', title: 'Los Guido'}
						
						]

                  },
                  {
					  title: 'Puriscal',
					  id: '04',
                      distritos:[
						  {id:'01', title: 'Santiago'},
						  {id:'02', title: 'Mercedes Sur'},
						  {id:'03', title: 'Barbacoas'},
						  {id:'04', title: 'Grifo Alto'},
						  {id:'05', title: 'San Rafael'},
						  {id:'06', title: 'Candelarita'},
						  {id:'07', title: 'Desamparaditos'},
						  {id:'08', title: 'San Antonio'},
						  {id:'09', title: 'Chires'}
						  
						 
						]
                  },
                  {
					  title: 'Tarrazú',
					  id: '05',
                      distritos:[
						  {id:'01', title: 'San Marcos'},
						  {id:'02', title: 'San Lorenzo'},
						  {id:'03', title: 'San Carlos'}
						 
						]
                  },
                  {
					  title: 'Aserrí',
					  id: '06',
                      distritos:[
						  {id:'01', title: 'Aserrí'},
						  {id:'02', title: 'Tarbaca'},
						  {id:'03', title: 'Vuelta De Jorco'},
						  {id:'04', title: 'San Gabriel'},
						  {id:'05', title: 'Legua'},
						  {id:'06', title: 'Monterrey'},
						  {id:'07', title: 'Salitrillos'}
						 
						  
						]
                  },
                  {
					  title: 'Mora',
					  id: '07',
                      distritos:[
						  {id:'01', title: 'Colón'},
						  {id:'02', title: 'Guayabo'},
						  {id:'03', title: 'Tabarcia'},
						  {id:'04', title: 'Piedras Negras'},
						  {id:'05', title: 'Picagres'},
						  {id:'06', title: 'Jaris'}
						 
						]
                  },
                  {
					  title: 'Goicoechea',
					  id: '08',
                      distritos:[
						{id:'01', title: 'Guadalupe'},
						  {id:'02', title: 'San Francisco'},
						  {id:'03', title: 'Calle Blancos'},
						  {id:'04', title: 'Mata De Platano'},
						  {id:'05', title: 'Ipís'},
						  {id:'06', title: 'Rancho Redondo'},
						  {id:'07', title: 'Purral'}
						   
						]
                  },
                  {
					  title: 'Santa Ana',
					  id: '09',
                      distritos:[
						{id:'01', title: 'Santa Ana'},
						  {id:'02', title: 'Salitral'},
						  {id:'03', title: 'Pozos'},
						  {id:'04', title: 'Uruca'},
						  {id:'05', title: 'Piedades'},
						  {id:'06', title: 'Brasil'}
						 
						]
                  },
                  {
					  title: 'Alajuelita',
					  id: '10',
                      distritos:[
						  {id:'01', title: 'Alajuelita'},
						  {id:'02', title: 'San Josecito'},
						  {id:'03', title: 'San Antonio'},
						  {id:'04', title: 'Concepción'},
						  {id:'05', title: 'San Felipe'}
						 
						]
                  },
                  {
					  title: 'Coronado',
					  id: '11',
                      distritos:[
						  {id:'01', title: 'San Isidro'},
						  {id:'02', title: 'San Rafael'},
						  {id:'03', title: 'Dulce Nombre De Jesus'},
						  {id:'04', title: 'Patalillo'},
						  {id:'05', title: 'Cascajal'}
						 
						]
                  },
                  {
					  title: 'Acosta',
					  id: '12',
                      distritos:[
						{id:'01', title: 'San Ignacio'},
						  {id:'02', title: 'Guaitil'},
						  {id:'03', title: 'Palmichal'},
						  {id:'04', title: 'Cangrejal'},
						  {id:'05', title: 'Sabanillas'}  
						
						]
                  },
                  {
					  title: 'Tibas',
					  id: '13',
                      distritos:[
						  {id:'01', title: 'San Juan'},
						  {id:'02', title: 'Cinco Esquinas'},
						  {id:'03', title: 'Anselmo Llorente'},
						  {id:'04', title: 'Leon Xiii'},
						  {id:'05', title: 'Colima'}    
						
						]
                  },
                  {
					  title: 'Moravia',
					  id: '14',
                      distritos:[
						 {id:'01', title: 'San Vicente'},
						  {id:'02', title: 'San Jeronimo'},
						  {id:'03', title: 'La Trinidad'}
						    
						
					   ]
                  },
                  {
					  title: 'Montes de Oca',
					  id: '15',
                      distritos:[
						{id:'01', title: 'San Pedro'},
						  {id:'02', title: 'Sabanilla'},
						  {id:'03', title: 'Mercedes'},
						  {id:'04', title: 'San Rafael'}
						 
					
						]

                  },
                  {
					  title: 'Turrubares',
					  id: '16',
                      distritos:[
						{id:'01', title: 'San Pablo'},
						  {id:'02', title: 'San Pedro'},
						  {id:'03', title: 'San Juan De Mata'},
						  {id:'04', title: 'San Luis'},
						   {id:'05', title: 'Carara'} 
						
						]
                  },
                  {
					  title: 'Dota',
					  id: '17',
                      distritos:[
						{id:'01', title: 'Santa María'},
						  {id:'02', title: 'Jardin'},
						  {id:'03', title: 'Copey'}
						 
						]
                  },
                  {
					  title: 'Curridabat',
					  id: '18',
                      distritos:[
						  {id:'01', title: 'Curridabat'},
						  {id:'02', title: 'Granadilla'},
						  {id:'03', title: 'Sanchez'},
						  {id:'04', title: 'Tirrases'}
						
						]
                  },
                  {
					  title: 'Pérez Zeledón',
					  id: '19',
                      distritos:[
						  {id:'01', title: 'San Isidro De El General'},
						  {id:'02', title: 'El General'},
						  {id:'03', title: 'Daniel Flores'},
						  {id:'04', title: 'Rivas'},
						  {id:'05', title: 'San Pedro'},
						  {id:'06', title: 'Platanares'},
						  {id:'07', title: 'Pejibaye'},
						  {id:'08', title: 'Cajon'},
						  {id:'09', title: 'Baru'},
						  {id:'10', title: 'Rio Nuevo'},
						  {id:'11', title: 'Páramo'}
						
						]
                  },
                  {
					  title: 'León Cortés',
					  id: '20',
                      distritos:[
						 {id:'01', title: 'San Pablo'},
						  {id:'02', title: 'San Andres'},
						  {id:'03', title: 'Llano Bonito'},
						  {id:'04', title: 'San Isidro'},
						  {id:'05', title: 'Santa Cruz'},
						  {id:'06', title: 'San Antonio'}
						 
						]
                  }

              ]
          },
          {
			  title: 'Alajuela',
			  id: '2',
              cantones : [
                  {
					  title: 'Alajuela',
					  id: '01',
                      distritos:[
						  {id:'01', title: 'Alajuela'},
						  {id:'02', title: 'San José'},
						  {id:'03', title: 'Carrizal'},
						  {id:'04', title: 'San Antonio'},
						  {id:'05', title: 'Guácima'},
						  {id:'06', title: 'San Isidro'},
						  {id:'07', title: 'Sabanilla'},
						  {id:'08', title: 'San Rafael'},
						  {id:'09', title: 'Rio Segundo'},
						  {id:'10', title: 'Desamparados'},
						  {id:'11', title: 'Turrucares'},
						  {id:'12', title: 'Tambor'},
						  {id:'13', title: 'Garita'},
						  {id:'14', title: 'Sarapiquí'}
						
						]
                  },
                  {
					  title: 'San Ramón',
					  id: '02',
                      distritos:[
						 {id:'01', title: 'San Ramón'},
						  {id:'02', title: 'Santiago'},
						  {id:'03', title: 'San Juan'},
						  {id:'04', title: 'Piedades Norte'},
						  {id:'05', title: 'Piedades Sur'},
						  {id:'06', title: 'San Rafael'},
						  {id:'07', title: 'San Isidro'},
						  {id:'08', title: 'Angeles'},
						  {id:'09', title: 'Alfaro'},
						  {id:'10', title: 'Volio'},
						  {id:'11', title: 'Concepción'},
						  {id:'12', title: 'Zapotal'},
						  {id:'13', title: 'Peñas Blancas'}
						
						]
                  },
                  {
					  title: 'Grecia',
					  id: '03',
                      distritos:[
							{id:'01', title: 'Grecia'},
							{id:'02', title: 'San Isidro'},
							{id:'03', title: 'San José'},
							{id:'04', title: 'San Roque'},
							{id:'05', title: 'Tacares'},
							{id:'06', title: 'Rio Cuarto'},
							{id:'07', title: 'Puente De Piedra'},
							{id:'08', title: 'Bolivar'}
						]
                  },
                  {
					  title: 'San Mateo',
					  id: '04',
                      distritos:[
							{id:'01', title: 'San Mateo'},
							{id:'02', title: 'Desmonte'},
							{id:'03', title: 'Jesús María'},
							{id:'04', title: 'Labrador'}
							]
                  },
                  {
					  title: 'Atenas',
					  id: '05',
                      distritos:[
						    {id:'01', title: 'Atenas'},
							{id:'02', title: 'Jesús'},
							{id:'03', title: 'Mercedes'},
							{id:'04', title: 'San Isidro'},
							{id:'05', title: 'Concepción'},
							{id:'06', title: 'San José'},
							{id:'07', title: 'Santa Eulalia'},
							{id:'08', title: 'Escobal'}
						 
						]
                  },
                  {
					  title: 'Naranjo',
					  id: '06',
                      distritos:[
						  {id:'01', title: 'Naranjo'},
						    {id:'02', title: 'San Miguel'},
							{id:'03', title: 'San José'},
							{id:'04', title: 'Cirrí Sur'},
							{id:'05', title: 'San Jerónimo'},
							{id:'06', title: 'San Juan'},
							{id:'07', title: 'El Rosario'},
							{id:'08', title: 'palmitos'}
							
						 ]
                  },
                  {
					  title: 'Palmares',
					  id: '07',
                      distritos:[
						  {id:'01', title: 'Palmares'},
						    {id:'02', title: 'Zaragoza'},
							{id:'03', title: 'Buenos Aires'},
							{id:'04', title: 'Santiago'},
							{id:'05', title: 'Candelaria'},
							{id:'06', title: 'Esquipulas'},
							{id:'07', title: 'La Granja'}
						]
                  },
                  {
					  title: 'Poás',
					  id: '08',
                      distritos:[
						 {id:'01', title: 'San Pedro'},
						    {id:'02', title: 'San Juan'},
							{id:'03', title: 'San Rafael'},
							{id:'04', title: 'Carrillos'},
							{id:'05', title: 'Sabana Redonda'}
							
						]
                  },
                  {
					  title: 'Orotina',
					  id: '09',
                      distritos:[
							{id:'01', title: 'Orotina'},
						    {id:'02', title: 'El Mastate'},
							{id:'03', title: 'Hacienda Vieja'},
							{id:'04', title: 'Coyolar'},
							{id:'05', title: 'La Ceiba'}  
						]
                  },
                  {
					  title: 'San Carlos',
					  id: '10',
                      distritos:[
							{id:'01', title: 'Quesada'},
						    {id:'02', title: 'Florencia'},
							{id:'03', title: 'Buenavista'},
							{id:'04', title: 'Aguas Zarcas'},
							{id:'05', title: 'Venecia'},
							{id:'06', title: 'Pital'},  
							{id:'07', title: 'La Fortuna'},  
							{id:'08', title: 'La Tigra'},  
							{id:'09', title: 'La Palmera'},  
							{id:'10', title: 'Venado'},
							{id:'11', title: 'Cutris'},
							{id:'11', title: 'Monterrey'},
							{id:'12', title: 'Pocosol'} 
						]
                  },
                  {
					  title: 'Zarcero',
					  id: '11',
                      distritos:[
							{id:'01', title: 'Zarcero'},
						    {id:'02', title: 'Laguna'},
							{id:'03', title: 'Tapesco'},
							{id:'04', title: 'Guadalupe'},
							{id:'05', title: 'Palmira'},
							{id:'06', title: 'Zapote'},  
							{id:'07', title: 'Brisas'}
							  
						]
                  },
                  {
					  title: 'Valverde Vega',
					  id: '12',
                      distritos:[
						  {id:'01', title: 'Sarchí Norte'},
						    {id:'02', title: 'Sarchí Sur'},
							{id:'03', title: 'Toro Amarillo'},
							{id:'04', title: 'San Pedro'},
							{id:'05', title: 'Rodriguez'}
						]
                  },
                  {
					  title: 'Upala',
					  id: '13',
                      distritos:[
						{id:'01', title: 'Upala'},
						    {id:'02', title: 'Aguas Claras'},
							{id:'03', title: 'San José o Pizote'},
							{id:'04', title: 'Bijagua'},
							{id:'05', title: 'Delicias'},
							{id:'06', title: 'Dos Rios'},  
							{id:'07', title: 'Yolillal'},
							{id:'08', title: 'Canalete'}  
						]
                  },
                  {
					  title: 'Los Chiles',
					  id: '14',
                      distritos:[
						    {id:'01', title: 'Los Chiles'},
						    {id:'02', title: 'Caño Negro'},
							{id:'03', title: 'El Amparo'},
							{id:'04', title: 'San Jorge'}
							  
						]
                  },
                  {
					  title: 'Guatuso',
					  id: '15',
                      distritos:[
						 {id:'01', title: 'San Rafael'},
						    {id:'02', title: 'Buenavista'},
							{id:'03', title: 'Cote'},
							{id:'04', title: 'Katira'}  
						]
                  }


              ]
          },
          {
			  title: 'Cartago',
			   id: '3',
              cantones : [
                  {
					  title: 'Cartago',
					   id: '01',
                      distritos:[
							{id:'01', title: 'Oriental'},
						    {id:'02', title: 'Occidental'},
							{id:'03', title: 'Carmen'},
							{id:'04', title: 'San Nicolás'},
							{id:'05', title: 'Aguacaliente o San Francisco'},
							{id:'06', title: 'Guadalupe o Arenilla'},  
							{id:'07', title: 'Corralillo'},  
							{id:'08', title: 'Tierra Blanca'},  
							{id:'09', title: 'Dulce Nombre'},  
							{id:'10', title: 'Llano Grande'},
							{id:'11', title: 'Quebradilla'}
						]
                  },
                  {
					  title: 'Paraíso',
					   id: '02',
                      distritos:[
							{id:'01', title: 'Paraiso'},
						    {id:'02', title: 'Santiago'},
							{id:'03', title: 'Orosi'},
							{id:'04', title: 'Cachí'},
							{id:'05', title: 'Llanos de Santa Lucía'}
							 
						]
                  },
                  {
					  title: 'La Unión',
					   id: '03',
                      distritos:[
							{id:'01', title: 'Tres Rios'},
						    {id:'02', title: 'San Diego'},
							{id:'03', title: 'San Juan'},
							{id:'04', title: 'San Rafael'},
							{id:'05', title: 'Concepción'},
							{id:'06', title: 'Dulce Nombre'},  
							{id:'07', title: 'San Ramón'},  
							{id:'08', title: 'Rio Azul'}
							
						]
                  },
                  {
					  title: 'Jiménez',
					   id: '04',
                      distritos:[
							{id:'01', title: 'Juan Viñas'},
						    {id:'02', title: 'Tucurrique'},
							{id:'03', title: 'Pejibaye'}
							 
						]
                  },
                  {
					  title: 'Turrialba',
					   id: '05',
                      distritos:[
							{id:'01', title: 'Turrialba'},
						    {id:'02', title: 'La Suiza'},
							{id:'03', title: 'Peralta'},
							{id:'04', title: 'Santa Cruz'},
							{id:'05', title: 'Santa Teresita'},
							{id:'06', title: 'Pavones'},  
							{id:'07', title: 'Tuis'},  
							{id:'08', title: 'Tayutic'},  
							{id:'09', title: 'Santa Rosa'},  
							{id:'10', title: 'Tres Equis'},
							{id:'11', title: 'La Isabel'},
							{id:'12', title: 'Chirripó'}  
						]
                  },
                  {
					  title: 'Alvarado',
					   id: '06',
                      distritos:[
							{id:'01', title: 'Pacayas'},
						    {id:'02', title: 'Cervantes'},
							{id:'03', title: 'Capellades'}  
						]
                  },
                  {
					  title: 'Oreamuno',
					   id: '07',
                      distritos:[
							{id:'01', title: 'San Rafael'},
						    {id:'02', title: 'Cot'},
							{id:'03', title: 'Potrero Cerrado'},
							{id:'04', title: 'Cipreses'},
							{id:'05', title: 'Santa Rosa'} 
						]
                  },
                  {
					  title: 'El Guarco',
					   id: '08',
                      distritos:[
							{id:'01', title: 'El Tejar'},
						    {id:'02', title: 'San Isidro'},
							{id:'03', title: 'Tobosi'},
							{id:'04', title: 'Patio De Agua'}
							
						]
                  }

              ]
          },
          {
			  title: 'Heredia',
			   id: '4',
              cantones : [
                  {
					  title: 'Heredia',
					   id: '01',
                      distritos:[
							{id:'01', title: 'Heredia'},
						    {id:'02', title: 'Mercedes'},
							{id:'03', title: 'San Francisco'},
							{id:'04', title: 'Ulloa'},
							{id:'05', title: 'Varablanca'}   
						]
                  },
                  {
					  title: 'Barva',
					   id: '02',
                      distritos:[
							{id:'01', title: 'Barva'},
						    {id:'02', title: 'San Pedro'},
							{id:'03', title: 'San Pablo'},
							{id:'04', title: 'San Roque'},
							{id:'05', title: 'Santa Lucía'},
							{id:'06', title: 'San José de la Montaña'}       
						]

                  },
                  {
					  title: 'Santo Domingo',
					   id: '03',
                      distritos:[
							{id:'01', title: 'Santo Domingo'},
						    {id:'02', title: 'San Vicente'},
							{id:'03', title: 'San Miguel'},
							{id:'04', title: 'Paracito'},
							{id:'05', title: 'Santo Tomás'},
							{id:'06', title: 'Santa Rosa'},
							{id:'07', title: 'Tures'},
							{id:'08', title: 'Para'}  
						]
                  },
                  {
					  title: 'Santa Bárbara',
					   id: '04',
                      distritos:[
							{id:'01', title: 'Santa Bárbara'},
						    {id:'02', title: 'San Pedro'},
							{id:'03', title: 'San Juan'},
							{id:'04', title: 'Jesús'},
							{id:'05', title: 'Santo Domingo'},
							{id:'06', title: 'Puraba'}
							 
						]
                  },
                  {
					  title: 'San Rafael',
					   id: '05',
                      distritos:[
						  {id:'01', title: 'San Rafael'},
						    {id:'02', title: 'San Josecito'},
							{id:'03', title: 'Santiago'},
							{id:'04', title: 'Los Ángeles'},
							{id:'05', title: 'Concepción'}
							
						  
						]
                  },
                  {
					  title: 'San Isidro',
					   id: '06',
                      distritos:[
							{id:'01', title: 'San Isidro'},
						    {id:'02', title: 'San José'},
							{id:'03', title: 'Concepción'},
							{id:'04', title: 'San Francisco'}
							
						]
                  },
                  {
					  title: 'Belén',
					   id: '07',
                      distritos:[
							{id:'01', title: 'San Antonio'},
						    {id:'02', title: 'La Ribera'},
							{id:'03', title: 'La Asuncion'}
							
						]
                  },
                  {
					  title: 'Flores',
					   id: '08',
                      distritos:[
							{id:'01', title: 'San Joaquín'},
						    {id:'02', title: 'Barrantes'},
							{id:'03', title: 'Llorente'}  
						]
                  },
                  {
					  title: 'San Pablo',
					   id: '09',
                      distritos:[
							{id:'01', title: 'San Pablo'},
						    {id:'02', title: 'Rincon De Sabanilla'}
						]
                  },
                  {
					  title: 'Sarapiquí',
					   id: '10',
                      distritos:[
							{id:'01', title: 'Puerto Viejo'},
						    {id:'02', title: 'La Virgen'},
							{id:'03', title: 'Las Horquetas'},
							{id:'04', title: 'Llanuras Del Gaspar'},
							{id:'05', title: 'Cureña'}
						]
                  }

              ]
          },
          {
			  title: 'Guanacaste',
			   id: '5',
              cantones : [
                  {
					  title: 'Liberia',
					  id: '01',
                      distritos:[
							{id:'01', title: 'Liberia'},
						    {id:'02', title: 'Cañas Dulces'},
							{id:'03', title: 'Mayorga'},
							{id:'04', title: 'Nacascolo'},
							{id:'05', title: 'Curubande'}  
						]
                  },
                  {
					  title: 'Nicoya',
					  id: '02',
                      distritos:[
							{id:'01', title: 'Nicoya'},
						    {id:'02', title: 'Mansion'},
							{id:'03', title: 'San Antonio'},
							{id:'04', title: 'Quebrada Honda'},
							{id:'05', title: 'Samara'},
							{id:'06', title: 'Nosara'}, 
							{id:'07', title: 'Belen de Nosarita'}
							 
						]
                  },
                  {
					  title: 'Santa Cruz',
					  id: '03',
                      distritos:[
							{id:'01', title: 'Santa Cruz'},
						    {id:'02', title: 'Bolson'},
							{id:'03', title: 'Veintisiete de Abril'},
							{id:'04', title: 'Tempate'},
							{id:'05', title: 'Cartagena'},
							{id:'06', title: 'Cuajiniquil'}, 
							{id:'07', title: 'Diria'},
							{id:'08', title: 'Cabo Velas'},
							{id:'09', title: 'Tamarindo'}
						]
                  },
                  {
					  title: 'Bagaces',
					  id: '04',
                      distritos:[
							{id:'01', title: 'Bagaces'},
						    {id:'02', title: 'Fortuna'},
							{id:'03', title: 'Mogote'},
							{id:'04', title: 'Rio Naranjo'} 
						]
                  },
                  {
					  title: 'Carrillo',
					  id: '05',
                      distritos:[
							{id:'01', title: 'Filadelfia'},
						    {id:'02', title: 'Palmira'},
							{id:'03', title: 'Sardinal'},
							{id:'04', title: 'Belen'}  
						]
                  },
                  {
					  title: 'Cañas',
					  id: '06',
                      distritos:[
							{id:'01', title: 'Cañas'},
						    {id:'02', title: 'Palmira'},
							{id:'03', title: 'San Miguel'},
							{id:'04', title: 'Bebedero'},
							{id:'05', title: 'Porozal'}    
						]
                  },
                  {
					  title: 'Abangares',
					  id: '07',
                      distritos:[
							{id:'01', title: 'Juntas'},
						    {id:'02', title: 'Sierra'},
							{id:'03', title: 'San Juan'},
							{id:'04', title: 'Colorado'}
						]
                  },
                  {
					  title: 'Tilarán',
					  id: '08',
                      distritos:[
						  	{id:'01', title: 'Tilaran'},
						    {id:'02', title: 'Quebrada Grande'},
							{id:'03', title: 'Tronadora'},
							{id:'04', title: 'Santa Rosa'},
							{id:'05', title: 'Libano'},
							{id:'06', title: 'Tierras Morenas'},
							{id:'07', title: 'Arenal'}   
						  ]
                  },
                  {
					  title: 'Nandayure',
					  id: '09',
                      distritos:[
							{id:'01', title: 'Carmona'},
						    {id:'02', title: 'Santa Rita'},
							{id:'03', title: 'Zapotal'},
							{id:'04', title: 'San Pablo'},
							{id:'05', title: 'Porvenir'},
							{id:'06', title: 'Bejuco'}
							    
						]
                  },
                  {
					  title: 'La Cruz',
					  id: '10',
                      distritos:[
							{id:'01', title: 'La Cruz'},
						    {id:'02', title: 'Santa Cecilia'},
							{id:'03', title: 'Garita'},
							{id:'04', title: 'Santa Elena'}
						 
						]
                  },
                  {
					  title: 'Hojancha',
					  id: '11',
                      distritos:[
							{id:'01', title: 'Hojancha'},
						    {id:'02', title: 'Monte Romo'},
							{id:'03', title: 'Puerto Carrillo'},
							{id:'04', title: 'Huacas'}  
						]
                  }

              ]
          },
          {
			  title: 'Puntarenas',
			   id: '6',
              cantones : [
                  {
					  title: 'Puntarenas',
					  id: '01',
                      distritos:[
						  	{id:'01', title: 'Puntarenas'},
						    {id:'02', title: 'Pitahaya'},
							{id:'03', title: 'Chomes'},
							{id:'04', title: 'Lepanto'},
							{id:'05', title: 'Paquera'},
							{id:'06', title: 'Manzanillo'},
							{id:'07', title: 'Guacimal'},
							{id:'08', title: 'Barranca'},
							{id:'09', title: 'Monte Verde'},
							{id:'10', title: 'Isla Del Coco'},
							{id:'11', title: 'Cóbano'},
							{id:'12', title: 'Chacarita'},
							{id:'13', title: 'Chira'},
							{id:'14', title: 'Acapulco'},
							{id:'15', title: 'El Roble'},
							{id:'16', title: 'Arancibia'}
						]
                  },
                  {
					  title: 'Esparza',
					  id: '02',
                      distritos:[
							{id:'01', title: 'Espíritu Santo'},
						    {id:'02', title: 'San Juan Grande'},
							{id:'03', title: 'Macacona'},
							{id:'04', title: 'San Rafael'},
							{id:'05', title: 'San Jerónimo'}
							 
						]
                  },
                  {
					  title: 'Buenos Aires',
					  id: '03',
                      distritos:[
							{id:'01', title: 'Buenos Aires'},
						    {id:'02', title: 'Volcán'},
							{id:'03', title: 'Potrero Grande'},
							{id:'04', title: 'Boruca'},
							{id:'05', title: 'Pilas'},
							{id:'06', title: 'Colinas'},
							{id:'07', title: 'Changuena'},
							{id:'08', title: 'Biolley'},
							{id:'09', title: 'Brunka'} 
						]
                  },
                  {
					  title: 'Montes de Oro',
					  id: '04',
                      distritos:[
							{id:'01', title: 'Miramar'},
						    {id:'02', title: 'La Unión'},
							{id:'03', title: 'San Isidro'}
						]
                  },
                  {
					  title: 'Osa',
					  id: '05',
                      distritos:[
							{id:'01', title: 'Puerto Cortés'},
						    {id:'02', title: 'Palmar'},
							{id:'03', title: 'Sierpe'},
							{id:'04', title: 'Bahía Ballena'},
							{id:'05', title: 'Piedras Blancas'},
							{id:'06', title: 'Bahía Drake'}  
						]
                  },
                  {
					  title: 'Quepos',
					  id: '06',
                      distritos:[
							{id:'01', title: 'Quepos'},
						    {id:'02', title: 'Savegre'},
							{id:'03', title: 'Naranjito'}
						]
                  },
                  {
					  title: 'Golfito',
					  id: '07',
                      distritos:[
							{id:'01', title: 'Golfito'},
						    {id:'02', title: 'Puerto Jiménez'},
							{id:'03', title: 'Guaycara'},
							{id:'04', title: 'Pavón'}
						]
                  },
                  {
					  title: 'Coto Brus',
					  id: '08',
                      distritos:[
							{id:'01', title: 'San Vito'},
						    {id:'02', title: 'Sabalito'},
							{id:'03', title: 'Aguabuena'},
							{id:'04', title: 'Limoncito'},
							{id:'05', title: 'Pittier'}
						]
                  },
                  {
					  title: 'Parrita',
					  id: '09',
                      distritos:[
						  {id:'01', title: 'Parrita'}
						]
                  },
                  {
					  title: 'Corredores',
					  id: '10',
                      distritos:[
						    {id:'01', title: 'Corredor'},
						    {id:'02', title: 'La Cuesta'},
							{id:'03', title: 'Canoas'},
							{id:'04', title: 'Laurel'}
						]
                  },
                  {
					  title: 'Garabito',
					  id: '11',
                      distritos:[
						{id:'01', title: 'Jacó'},
						{id:'02', title: 'Tárcoles'}, 
						]
                  }

              ]
          },
          {
			  title: 'Limón',
			   id: '7',
              cantones : [
                  {
					  title: 'Limón',
					  id: '01',
                      distritos:[
						 	{id:'01', title: 'Limón'},
						    {id:'02', title: 'Valle La Estrella'},
							{id:'03', title: 'Rio Blanco'},
							{id:'04', title: 'Matama'}  
						]
                  },
                  {
					  title: 'Pococí',
					  id: '02',
                      distritos:[
							{id:'01', title: 'Guapiles'},
						    {id:'02', title: 'Jiménez'},
							{id:'03', title: 'Rita'},
							{id:'04', title: 'Roxana'},
							{id:'05', title: 'Cariari'},
							{id:'06', title: 'Colorado'},
							{id:'07', title: 'La Colonia'}
						]
                  },
                  {
					  title: 'Siquirres',
					  id: '03',
                      distritos:[
							{id:'01', title: 'Siquirres'},
						    {id:'02', title: 'Pacuarito'},
							{id:'03', title: 'Florida'},
							{id:'04', title: 'Germania'},
							{id:'05', title: 'El Cairo'},
							{id:'06', title: 'Alegría'}  
						]
                  },
                  {
					  title: 'Talamanca',
					  id: '04',
                      distritos:[
							{id:'01', title: 'Bratsi'},
						    {id:'02', title: 'Sixaola'},
							{id:'03', title: 'Cahuita'},
							{id:'04', title: 'Telire'}
						]
                  },
                  {
					  title: 'Matina',
					  id: '05',
                      distritos:[
							{id:'01', title: 'Matina'},
						    {id:'02', title: 'Batán'},
							{id:'03', title: 'Carrandi'}  
						]
                  },
                  {
					  title: 'Guácimo',
					  id: '06',
                      distritos:[
							{id:'01', title: 'Guácimo'},
						    {id:'02', title: 'Mercedes'},
							{id:'03', title: 'Pocora'},
							{id:'04', title: 'Rio Jiménez'},
							{id:'05', title: 'Duacari'}    
						]
                  }

              ]
          }

          ];

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

	@if($user->configFactura)
	  	setTimeout(function(){

                $('#provincia option[value="{{ $user->configFactura->provincia }}"]').attr("selected", true);
                $('#provincia').change();
                $('#canton option[value="{{ $user->configFactura->canton }}"]').attr("selected", true);
				$('#canton').change();
				 $('#distrito option[value="{{ $user->configFactura->distrito }}"]').attr("selected", true);
            }, 100);
	@endif

});
</script>
@endsection
