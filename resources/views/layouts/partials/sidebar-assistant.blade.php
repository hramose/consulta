<!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <!-- <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
         <img src="{{ getAvatar(auth()->user()) }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ auth()->user()->name }}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">MENU</li>
        <!-- Optionally, you can add icons to the links -->
        <li><a href="{{ url('/')}}"><i class="fa fa-home"></i> <span>Home</span></a></li>
        <li><a href="{{ url('/assistant/appointments')}}"><i class="fa fa-calendar"></i> <span>Agenda</span></a></li>
         <li><a href="{{ url('/assistant/medics')}}"><i class="fa fa-user-md"></i> <span>Médicos</span></a></li>
        <li><a href="{{ url('/assistant/patients')}}"><i class="fa fa-users"></i> <span>Pacientes</span></a></li>
        <li><a href="{{ url('/assistant/invoices')}}"><i class="fa fa-money"></i> <span>Facturación</span></a></li>
        <li><a href="{{ url('/assistant/invoices/balance')}}"><i class="fa fa-dollar"></i> <span>Cierre del Dia</span></a></li>
       
        
        
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->