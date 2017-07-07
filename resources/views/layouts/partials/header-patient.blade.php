
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="/" class="navbar-brand"><img src="/img/logo-white-50x50.png" alt="{{ config('app.name', 'Laravel') }}"></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="{{ url('/appointments') }}">Consultas Programadas</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            
            <!-- User Account Menu -->
              <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <!-- <img src="/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
                <img src="{{ getAvatar(auth()->user()) }}" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"> {{ auth()->user()->name }} - ID:{{ (auth()->user()->patients->first()) ? auth()->user()->patients->first()->id : ''}}</span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <!-- <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                  <img src="{{ getAvatar(auth()->user()) }}" class="img-circle" alt="User Image">
                  <p>
                     {{ auth()->user()->name }}
                    <small>Member since {{ auth()->user()->created_at->diffForHumans() }}</small>
                  </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                  
                  <!-- /.row -->
                </li>
                <!-- Menu Footer-->

                <li class="user-footer">
                   <div class="pull-left">
                    <a href="{{ url('/account/edit') }}" class="btn btn-default btn-flat">Perfil</a>
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
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>






   