<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

      <!-- Bootstrap 3.3.6s -->
      <!-- <link rel="stylesheet" href="/css/bootstrap.min.css"> -->
      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
      <!-- Ionicons -->
      <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"> -->
      <!-- Theme style -->
      <link href="{{ elixir('/css/app.css') }}" rel="stylesheet">
       <link href="/js/plugins/magnific-popup/magnific-popup.css" rel="stylesheet">
      <link href="/js/plugins/slick/slick.css" rel="stylesheet">

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
            'fe' => Auth::user()->fe,
        ]); ?>
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
 @include('layouts/partials/preloader')
<div id="app" class="wrapper">

  <!-- Main Header -->
  <header class="main-header">
      @include('layouts/partials/header')
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
      
      @include('layouts/partials/sidebar')
    
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper {{ (Request::segment(1)) ? 'bg-'.Request::segment(2) : 'bg-home' }}">
    <!-- @include('layouts/partials/flash-message') -->
    
    
    
     
    <alert :type="message.type" v-show="message.show" >@{{ message.text }}</alert>
 

    @if (session()->has('flash_message'))

      <alert type="{!! session()->get('flash_message_level') !!}" >{!! session()->get('flash_message') !!}</alert>

    @endif
     
  
  

     @if($monthlyCharge->count() || $userOfficesindependientes->count() || !auth()->user()->active)
          <div  class="notification-app alert-danger">
              <div class="slider-notifications">
                  
                        @if(! Request::is('medic/payments/create') && ! Request::is('medic/payments/*/create') && ! Request::is('medic/subscriptions/*/change') && ! Request::is('medic/subscriptions/*/buy')) 
                        
                            @foreach($monthlyCharge as $charge)
                                @if($charge->type == 'M')
                                  <div  class="item notification-app-item" >
                                    Tienes un monto pendiente de <b>{{ money($charge->amount,'$') }}</b> a pagar por citas atendidas! <a href="#" data-toggle="modal" data-target="#modalPaymentDetail-{{ $charge->id }}">Ver Detalles</a>  
                                      <a href="{{ url('/medic/payments/'. $charge->id .'/create') }}" class="btn btn-info btn-sm">Pagar</a>
                                     
                                  </div>
                                  
                              
                                @else 

                                  <div  class="item notification-app-item" >
                                    Tu subscripción ha vencido!! Renueva o cambia de Plan si deseas continuar..<a href="{{ url('/medic/payments/'. $charge->id .'/create') }}" class="btn btn-info btn-sm">Renovar</a> <a href="#" data-toggle="modal" data-target="#modalSubscriptionChange" class="btn btn-danger btn-sm">Cambiar de plan</a>
                                   
                                  </div>
                                
                                @endif
                              @endforeach
                            @endif
                   
                       
                              
                            @foreach($userOfficesindependientes as $office)
                              
                                  <div class="item notification-app-item ">
                                   
                                    <form method="POST" action="{{ url('/medic/account/offices/'. $office->id .'/notification') }}" class="form-horizontal form-update-location">
                                          {{ csrf_field() }}<input name="_method" type="hidden" value="PUT">
                                           ACTUALIZAR UBICACIÓN CONSULTORIO {{ $office->name }} 
                                          <input class="form-control" type="hidden" name="notification" value="0">
                                          <input class="form-control" type="hidden" name="id" value="{{ $office->id }}">
                                      <button type="submit" class="btn btn-success btn-sm">Actualizar con tu ubicación actual</button>
                                    </form>
                                  </div>
                              
                              @endforeach
                              
                        
                          @if( !auth()->user()->active )
                             <div class="item notification-app-item ">     
                                    
                            Esta cuenta esta inactiva mientras el administrador verifica tus datos. Puedes seguir editando tus opciones mientras se activa. <a class="popup-youtube" href="http://www.youtube.com/watch?v=DrYMxb-7WQI">EMPIECE AQUI!</a>
                                
                            </div>
                          @endif
                     
                   
              </div>

              
              
            
          </div>
            @foreach($monthlyCharge as $charge)
                  @if($charge->type == 'M')
                    <div class="modal fade" id="modalPaymentDetail-{{ $charge->id }}" role="dialog" aria-labelledby="modalPaymentDetail">
                      <div class="modal-dialog " role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                          
                          <h4 class="modal-title" id="modalPaymentDetailLabel">Detalle de Pago</h4>
                          </div>
                          <div class="modal-body" >
                            

                        <payment-details income_id="{{ $charge->id }}" ></payment-detials>
                            
                            
                              
                          </div>
                          <div class="modal-footer" >
                          
                          
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                          
                          </div>
                        </div>
                      </div>
                    </div>
                @else
                    @include('layouts/partials/modal-subscriptions-change',['change' => 1])        
                @endif
            @endforeach
        @endif

     @if(! Request::is('/'))
     <div class="menu-fixed">
          <div class="menu-fixed-container">
            <a href="/medic/appointments" class="btn btn-sm btn-info {{ set_active('medic/appointments') }}">Agenda</a>
            <a href="/medic/appointments/create?wizard=1" class="btn btn-sm btn-success {{ set_active('medic/appointments/create') }}">Programe</a>
            <a href="/medic/account/edit?tab=clinics" class="btn btn-sm btn-warning {{ set_active('medic/account/edit') }}">Consultorios</a>
            <a href="/medic/patients" class="btn btn-sm btn-danger {{ set_active('medic/patients') }}">Pacientes</a>
            <!-- <a href="/medic/patients?inita=1" class="btn btn-sm btn-default bg-purple {{ set_active('medic/patients?inita=1') }}">Iniciar Consulta</a> -->
          </div>
       </div>

    @endif

    <section class="content menu">
      @include('layouts/partials/home-boxes')  
    </section>
    
    @yield('content')
    
  </div>
  <!-- /.content-wrapper -->


  @include('layouts/partials/footer')

 
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<script src="{{ elixir('js/app.js') }}"></script>
<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 2.2.3 -->
<script src="/js/plugins/jQuery/jquery-2.2.3.min.js"></script> 
<script src="/js/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="/js/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="/js/plugins/slick/slick.min.js"></script>
 <!-- <script src="/js/plugins/hopscotch/js/hopscotch.min.js"></script>  -->
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


</body>
</html>

