
    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>C</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Consulta</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <!-- <img src="/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
              <img src="{{ Storage::url('avatars/'.auth()->user()->id.'/avatar.jpg') }}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <!-- <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                <img src="{{ Storage::url('avatars/'.auth()->user()->id.'/avatar.jpg') }}" class="img-circle" alt="User Image">
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
                    <a href="{{ url('/medic/account/edit') }}">Perfil</a>
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
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>