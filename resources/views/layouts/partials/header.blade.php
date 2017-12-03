
    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b><img src="/img/logo-white-50x50.png" alt="{{ config('app.name', 'Laravel') }}"></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><img src="/img/logo-white-50x50.png" alt="{{ config('app.name', 'Laravel') }}"> {{ config('app.name', 'Laravel') }}</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      @if($clinicsUser->count())
        <form method="POST" action="{{ url('/changeoffice') }}" class="form-changeoffice">
        {{ csrf_field() }}
          <select name="selected_clinic" id="selected_clinic" class="form-control">
          
            @foreach($clinicsUser as $userClinic)
              <option value="{{  $userClinic->id }}" {{ (Session::has('office_id') && $userClinic->id == Session::get('office_id')) ? 'selected' : '' }}>{{  $userClinic->name }}</option>
            @endforeach
          </select>
          
        </form>
      @endif

     
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <notifications :appointments="{{ $newAppointments }}" :total="{{ $newAppointments->count() }}" :user-id="{{ auth()->id() }}"></notifications>
          
          <!-- <li class="dropdown notifications-menu">
            <a href="/medic/appointments" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">  @if($count = $newAppointments->count() > 0)
                            {{ $count }} 
                   @endif
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes {{ $count }} nueva(s) cita(s) reservada(s)</li>
              <li>
               
                <ul class="menu">
                  @foreach($newAppointments as $appointment)
                  <li>
                    <a href="#">
                      <i class="fa fa-calendar text-aqua"></i> {{ $appointment->title }} -  {{ \Carbon\Carbon::parse($appointment->start)->toDateTimeString() }}
                    </a>
                  </li>
                  @endforeach
                
                </ul>
              </li>
            
              <li class="footer"><a href="/medic/appointments">Ver todas</a></li>
            </ul>
          </li> -->
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <!-- <img src="/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
        
              
              <img src="{{ getAvatar(auth()->user()) }}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                
               <img src="{{ getAvatar(auth()->user()) }}" class="img-circle" alt="User Image">

                <p>
                   {{ auth()->user()->name }}
                  <small>Member since {{ auth()->user()->created_at->diffForHumans() }}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="{{ url('/medic/patients') }}">Pacientes</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="{{ url('/medic/appointments') }}">Consultas</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="{{ url('/medic/account/edit?tab=assistant') }}">Asistentes</a>
                  </div>
                  
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->

              <li class="user-footer">
                 <div class="pull-left">
                  <a href="{{ url('/medic/account/edit') }}" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>
                  <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="modal" data-target="#contact-modal" data-user="{{ auth()->user()->email }}"><i class="fa fa-question"></i></a>
          </li>
        </ul>
      </div>
    </nav>