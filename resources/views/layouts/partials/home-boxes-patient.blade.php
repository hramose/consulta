<div class="row boxes-home  {{ (! Request::is('/')) ? 'none' : '' }} " >
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>Buscar médico</h3>

                  <p>Doctores</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="/" class="small-box-footer">Buscar <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>Buscar Clínica</h3>

                  <p>Consultorios o clínicas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="/clinics/search" class="small-box-footer">Buscar <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/clinics/search" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>Pacientes</h3>

                  <p>Crea pacientes</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="/account/edit?tab=patients" class="small-box-footer">Ir a Paciente <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/account/edit?tab=patients" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box  bg-yellow">
                <div class="inner">
                  <h3>Historial de citas</h3>

                  <p>Citas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="/appointments" class="small-box-footer">Ir a Citas <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/appointments" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            
          </div>