<div class="row boxes-home  {{ (! Request::is('/')) ? 'none' : '' }} " >
      

            <div class="col-xs-12 col-sm-3 col-lg-3  ">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>Usuarios</h3>

                  <p>Medicos o pacientes</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="/admin/users/" class="small-box-footer">Ir a usuarios <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/admin/users/" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3 ">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>Administradores <span data-toggle="tooltip" title="" class="badge bg-red" data-original-title="{{ $admins }} Solicitudes Nuevas">{{ $admins }}</span></span> </h3>

                  <p>Clínicas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="/admin/clinics/requests" class="small-box-footer">Ir a Solicitudes <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/admin/clinics/requests" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
             <div class="col-xs-12 col-sm-3 col-lg-3 ">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>Médicos <span data-toggle="tooltip" title="" class="badge bg-red" data-original-title="{{ $medics }} Solicitudes Nuevas">{{ $medics }}</span></span></h3>

                  <p>Solicitudes</p>
                </div>
                <div class="icon">
                  <i class="ion ion-app"></i>
                </div>
                <a href="/admin/medics/requests" class="small-box-footer">Ir a Solicitudes <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/admin/medics/requests" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3 ">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3>Clínicas <span data-toggle="tooltip" title="" class="badge bg-red" data-original-title="{{ $requests }} Solicitudes Nuevas">{{ $requests }}</span></span></h3>

                  <p>Solicitudes</p>
                </div>
                <div class="icon">
                  <i class="ion ion-home"></i>
                </div>
                <a href="/admin/offices/requests" class="small-box-footer">Ir a Solicitudes <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/admin/offices/requests" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            
        

                
</div>