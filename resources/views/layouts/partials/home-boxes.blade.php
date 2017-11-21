<div class="row boxes-home  {{ (! Request::is('/')) ? 'none' : '' }} " >
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>Agenda</h3>

                  <p>Consultas</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="#" data-toggle="modal" data-target="#modalSelectClinic" class="small-box-footer">Ir a consultas <i class="fa fa-arrow-circle-right"></i></a>
                <a href="#" data-toggle="modal" data-target="#modalSelectClinic" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>Programe</h3>

                  <p>su Agenda</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="/medic/appointments/create?wizard=1" class="small-box-footer">Ir a agenda <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/medic/appointments/create?wizard=1" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>Consultorios</h3>

                  <p>Modifique su consultorio</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="/medic/account/edit?tab=clinics" class="small-box-footer">Ir a consultorios <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/medic/account/edit?tab=clinics" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>Pacientes</h3>

                  <p>Expediente Clínico</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="/medic/patients" class="small-box-footer">Ir a pacientes <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/medic/patients" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            
          </div>