<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

      <!-- Bootstrap 3.3.6 -->
      <!-- <link rel="stylesheet" href="/css/bootstrap.min.css"> -->
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
      <!-- Ionicons -->
      <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"> -->
      <!-- Theme style -->
      <link href="/js/plugins/slick/slick.css" rel="stylesheet">
      <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">

       @yield('css')
      
      <!-- <link rel="stylesheet" href="/css/AdminLTE.min.css"> -->
      <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
            page. However, you can choose any other skin. Make sure you
            apply the skin class to the body tag so the changes take effect.
      -->
      <!-- <link rel="stylesheet" href="/css/skin-blue.min.css"> -->

    <!-- Styles -->
    
    

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
 @include('layouts/partials/preloader')
<div id="app" class="wrapper">

  <!-- Main Header -->
  <header class="main-header">
      @include('layouts/partials/header-clinic')
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
      
      @include('layouts/partials/sidebar-clinic')
    
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper {{ (Request::segment(1)) ? 'bg-'.Request::segment(2) : 'bg-home' }}">
    <!-- @include('layouts/partials/flash-message') -->
    
    <alert :type="message.type" v-show="message.show" >@{{ message.text }}</alert>
    
    @if (session()->has('flash_message'))

      <alert type="{!! session()->get('flash_message_level') !!}" >{!! session()->get('flash_message') !!}</alert>

    @endif


        @if(count($notifications))
          <div  class="notification-app alert-danger">
              <div class="slider-notifications">

                              @if( !auth()->user()->active )
                                <div class="item notification-app-item ">     
                                        
                                    Esta cuenta esta inactiva mientras el administrador verifica tus datos!
                                    
                                </div>
                              @endif
                  
                            @foreach(auth()->user()->offices as $office)

                                  @if($office->fe && !$office->configFactura->first())
                                    <div class="item notification-app-item">
                                        

                                          <p>Haz seleccionado que vas a utilizar la factura electronica de hacienda. Por favor recuerda que debes configurar los parametros iniciales para funcionar correctamente. Puedes realizarlo desde este link. <a href="/clinic/account/edit?tab=fe" title="Ir a configurar Factura Electrónica"><b>Configurar Factura Electrónica</b></a></p>
                                    </div>
                                  @endif
                              
                                   @if($office->notification && $office->notification_date != '0000-00-00 00:00:00')
                                    <div  class="item notification-app-item">
                                      
                                      <form method="POST" action="{{ url('/clinic/account/offices/'. $office->id .'/notification') }}" class="form-horizontal form-update-location" data-role="clinic">
                                            {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
                                            ACTUALIZAR UBICACIÓN CONSULTORIO {{ $office->name }} 
                                            <input type="hidden" name="notification" value="0">
                                            <input type="hidden" name="id" value="{{ $office->id }}">
                                        <button type="submit" class="btn btn-success btn-sm">Actualizar con tu ubicación actual</button>
                                      </form>
                                    </div>
                                    @endif 
                              
                              @endforeach
                              
                        
                        
                     
                   
              </div>

              
              
            
          </div>
          @endif 
    



      @if(! Request::is('/'))
     <div class="menu-fixed">
          <div class="menu-fixed-container">
            <a href="/clinic/appointments" class="btn btn-sm btn-info {{ set_active('clinic/appointments') }}">Agenda</a>
            <a href="/clinic/medics" class="btn btn-sm btn-success {{ set_active('clinic/medics') }}">Medicos</a>
            <a href="/clinic/patients" class="btn btn-sm btn-danger {{ set_active('clinic/patients') }}">Pacientes</a>
            <a href="/clinic/reports" class="btn btn-sm btn-warning {{ set_active('clinic/reports') }}">Reportes</a>
            <!-- <a href="/medic/patients?inita=1" class="btn btn-sm btn-default bg-purple {{ set_active('medic/patients?inita=1') }}">Iniciar Consulta</a> -->
          </div>
       </div>
    @endif 
    <section class="content menu">
        @include('layouts/partials/home-boxes-clinic')  
    </section> 

    @if(auth()->user()->active)
      @yield('content')
    @endif
  </div>
  <!-- /.content-wrapper -->

  @include('layouts/partials/footer')

  <!-- Control Sidebar -->
  <!--<aside class="control-sidebar control-sidebar-dark">
    
  </aside>-->
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<script src="{{ elixir('js/app.js') }}"></script>
<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 2.2.3 -->
<script src="/js/plugins/jQuery/jquery-2.2.3.min.js"></script> 
<script src="/js/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="/js/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="/js/plugins/slick/slick.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<!--<script src="/js/bootstrap.min.js"></script>-->
<!-- AdminLTE App -->
<script src="{{ elixir('js/app-theme.min.js') }}"></script>
<script>
$(document).ready(function(){

  $('.slider-notifications').slick({
    prevArrow: '<span class="fa fa-angle-left"></span>',
    nextArrow: '<span class="fa fa-angle-right"></span>'
  });

});
</script>

 @yield('scripts')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>

