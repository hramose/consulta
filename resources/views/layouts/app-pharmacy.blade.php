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
      @include('layouts/partials/header-pharmacy')
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
      
      @include('layouts/partials/sidebar-pharmacy')
    
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper ">
    <!-- @include('layouts/partials/flash-message') -->
    
   
    <alert :type="message.type" v-show="message.show" >@{{ message.text }}</alert>
    
    <!-- @if(!auth()->user()->active)
       <div  class="notification-app alert-danger" >Esta cuenta esta inactiva mientras el administrador verifica tus datos!</div> 
     @endif -->

    @if (session()->has('flash_message'))

      <alert type="{!! session()->get('flash_message_level') !!}" >{!! session()->get('flash_message') !!}</alert>

    @endif

     


        <div  class="notification-app alert-warning" style="margin-bottom: 1rem; height:60px;">
             <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="height:50px;">
               
                <div class="carousel-inner">
                    
                    @if( auth()->user()->pharmacies->first() && auth()->user()->pharmacies->first()->notification && auth()->user()->pharmacies->first()->notification_date != '0000-00-00 00:00:00')
                        <div class="item notification-app-item active">
                        
                            ACTUALIZAR UBICACIÓN FARMACIA {{ auth()->user()->pharmacies->first()->name }} 
                            <form method="POST" action="{{ url('/pharmacy/account/pharmacies/'. auth()->user()->pharmacies->first()->id .'/notification') }}" class="form-horizontal form-update-location" data-role="pharmacy">
                                    {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
                                    <input type="hidden" name="notification" value="0">
                                    <input type="hidden" name="id" value="{{ auth()->user()->pharmacies->first()->id }}">
                                <button type="submit" class="btn btn-success btn-sm">Actualizar con tu ubicación actual</button>
                            </form>
                        </div>
                   @endif 
                @if(!auth()->user()->active)
                        <div class="item notification-app-item">
                            
                            Esta cuenta esta inactiva mientras el administrador verifica tus datos!
                        
                        </div>
                   @endif
                  
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                  <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                  <span class="fa fa-angle-right"></span>
                </a>
              </div>
           
        </div>
        
       
   
      @if(! Request::is('/'))
     <div class="menu-fixed">
          <div class="menu-fixed-container">
           
            <a href="/clinic/patients" class="btn btn-sm btn-danger {{ set_active('clinic/patients') }}">Pacientes</a>
            
            <!-- <a href="/medic/patients?inita=1" class="btn btn-sm btn-default bg-purple {{ set_active('medic/patients?inita=1') }}">Iniciar Consulta</a> -->
          </div>
       </div>
    @endif 
      <section class="content menu">
        @include('layouts/partials/home-boxes-pharmacy')  
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
<!-- Bootstrap 3.3.6 -->
<!--<script src="/js/bootstrap.min.js"></script>-->
<!-- AdminLTE App -->
<script src="{{ elixir('js/app-theme.min.js') }}"></script>


 @yield('scripts')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>

