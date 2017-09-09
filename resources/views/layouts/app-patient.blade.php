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
<body class="hold-transition skin-blue layout-top-nav">
<div class="preloader">
    <div class="img">
      <img src="/img/loader.gif" alt="Preloader image">
      <span>Cargando...</span>
    </div>
    
</div>
<div id="app" class="wrapper ">

  <!-- Main Header -->
  <header class="main-header">
      @include('layouts/partials/header-patient')
  </header>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper {{ (Request::segment(1)) ? 'bg-'.Request::segment(1) : 'bg-home' }}">
    <div class="container">
      @if(! Request::is('/') && ! Request::is('home'))
        <div class="menu-fixed">
            <div class="menu-fixed-container">
              <a href="/" class="btn btn-sm btn-info {{ set_active('/') }}" >Buscar Médico</a>
              <a href="/clinics/search" class="btn btn-sm btn-success {{ set_active('clinics/search') }}">Buscar Clínica</a>
              <a href="/account/edit?tab=patients" class="btn btn-sm btn-danger {{ set_active('account/edit') }}">Crear Pacientes</a>
             
              @if(auth()->user()->patients->count())
                <a href="/expedients/{{ auth()->user()->patients->first()->id }}/show" class="btn btn-sm btn-warning {{ set_active('appointments') }} ">Mi Expediente</a>
              @endif
            </div>
         </div>
         <section class="content menu">
          @include('layouts/partials/home-boxes-patient')  
        </section> 
        @endif 
        
        <alert :type="message.type" v-show="message.show" >@{{ message.text }}</alert>

         @if(!auth()->user()->active)
           <div  class="notification-app alert-danger" >Esta cuenta esta inactiva. Ponte en contacto con el administrador del sitio!</div> 
         @endif

        @if (session()->has('flash_message'))

          <alert type="{!! session()->get('flash_message_level') !!}" >{!! session()->get('flash_message') !!}</alert>

        @endif
         @if(!auth()->user()->patients->count())
           <div  class="notification-app alert-warning" >Recuerda agregar tus <a href="/account/edit?tab=patients" title="Ir a pacientes">pacientes</a> para poder realizar citas en linea!</div> 
         @endif
         
        @yield('content')
    </div>
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
<!-- <script src="/js/plugins/slimScroll/jquery.slimscroll.min.js"></script> -->
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

