@extends('layouts.app-pharmacy')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
   <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
   <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css"> 
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
                <img class="profile-user-img img-responsive img-circle" src="{{ getAvatar(auth()->user()) }}" alt="User profile picture">
              
              <h3 class="profile-username text-center">{{ $user->name }}</h3>

              <p class="text-muted text-center">{{ $user->roles->first()->name }}</p>

               <a class="UploadButton btn btn-primary btn-block" id="UploadPhoto" data-url="/pharmacy/account/avatars">Subir Foto</a>
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
              <li class="{{ isset($tab) ? ($tab =='profile') ? 'active' : '' : 'active' }}"><a href="#profile" data-toggle="tab">Perfil administrador</a></li>
              <li class="{{ isset($tab) ? ($tab =='pharmacies') ? 'active' : '' : '' }}"><a href="#pharmacies" data-toggle="tab">Información de la Farmacia</a></li>
              
              
            </ul>
            <div class="tab-content">
              <div class="{{ isset($tab) ? ($tab =='profile') ? 'active' : '' : 'active' }} tab-pane" id="profile">
                 <form method="POST" action="{{ url('/pharmacy/account/edit') }}" class="form-horizontal">
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
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                  </div>
                </form>


              </div>
              <!-- /.tab-pane -->
              <div class="{{ isset($tab) ? ($tab =='pharmacies') ? 'active' : '' : '' }} tab-pane" id="pharmacies">
                  <pharmacy :pharmacy="{{ $user->pharmacies->first() }}"></pharmacy>
                   <!-- <office :offices="{{ $user->offices }}"></office> -->
              </div>
              <!-- /.tab-pane -->
              <!-- <div class="{{ isset($tab) ? ($tab =='assistant') ? 'active' : '' : '' }} tab-pane" id="assistant">
                  <assistant-pharmacy-form :pharmacies="{{ $user->pharmacies()->get() }}" url="/pharmacy/account/assistant"></assistant-pharmacy-form>
                   <h3>Tus Asistentes registrados</h3> 
                   <assistant-list :assistants="{{ $assistants }}" url="/pharmacy/account/assistants"></assistant-list>
                  
              </div> -->
             
             
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
 <script src="{{ elixir('/js/ubicaciones.min.js') }}"></script>
<script>
  $(function () {
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
  

    $(".select2").select2();

    $("#UploadPhoto").ajaxUpload({
      url : $("#UploadPhoto").data('url'),
      name: "photo",
      data: {},
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



});
</script>
@endsection
