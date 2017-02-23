@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
   <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 
@endsection
@section('content')
    
    <div id="infoBox" class="alert alert-success" ></div>
  @include('layouts/partials/header-pages',['page'=>'Perfil'])


    <section class="content">
      
      <div class="row">
        <div class="col-md-4">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <!--<img class="profile-user-img img-responsive img-circle" src="/img/user4-128x128.jpg" alt="User profile picture">-->
              <img class="profile-user-img img-responsive img-circle" src="{{ Storage::url('avatars/'.auth()->user()->id.'/avatar.jpg') }}" alt="User profile picture">
              
              <h3 class="profile-username text-center">{{ $user->name }}</h3>

              <p class="text-muted text-center">{{ $user->specialities->first()->name }}</p>

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
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Perfil</a></li>
              <li><a href="#timeline" data-toggle="tab">Consultorios</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
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
                    <label for="speciality_id" class="col-sm-2 control-label">Especialidad</label>

                    <div class="col-sm-10">
                      <select class="form-control select2" style="width: 100%;" name="speciality[]" placeholder="-- Selecciona Especialidad --" multiple required>
                        <option value="">Especialidad</option>
                        @foreach($specialities as $speciality)
                            <option value="{{$speciality->id}}" @foreach($user->specialities as $s) @if($speciality->id == $s->id)selected="selected"@endif @endforeach>{{$speciality->name}}</option>
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
                  <div class="callout callout-info">
                    <h4>Informacion importante!</h4>

                    <p>Agrega los consultorios donde brindarás consulta privada</p>
                  </div>
                   <office :offices="{{ $user->offices }}"></office>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/es.js"></script>
<script src="/js/bootstrap.min.js"></script>

 <script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script> 
<script>
  $(function () {
    //Initialize Select2 Elements
    $('#datetimepicker1').datetimepicker({
            format:'YYYY-MM-DD hh:mm',
            locale: 'es',
            
         });
    $('#modalOfficeNotification').find('.btn-save-notification').on('click',function (e) {
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

    });

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
