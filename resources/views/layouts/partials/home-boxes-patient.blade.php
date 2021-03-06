<div class="row boxes-home  {{ (! Request::is('/')) ? 'none' : '' }} " >
            <div class="col-lg-3 col-sm-3 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-aqua" style="position: relative;">
                <div class="inner">
                  <h3>Buscar médico</h3>

                  <div class="row">
                    <div class=" col-xs-12 col-sm-5">
                        <a href="/medics/search?general=1" class="btn btn-default">General</a>
                        <!-- <div class="small-box bg-white">
                          <div class="inner">
                            <span>General </span>
                            <a href="/medics/search?general=1"></a>
                          </div>
                        </div> -->
                      </div>
                      <div class=" col-xs-12 col-sm-5">
                         <a href="/medics/search?specialist=1" class="btn btn-default">Especialista</a>
                        <!-- <div class="small-box bg-blank">
                          <div class="inner">
                            <span>Especialista</span>
                            <a href="/medics/search?specialist=1"></a>
                          </div>
                        </div> -->
                      </div>
                  </div>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="/" class="small-box-footer">Buscar <i class="fa fa-arrow-circle-right"></i></a>
                <!-- <a href="/" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-3 col-xs-12">
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
            <div class="col-lg-3 col-sm-3 col-xs-12">
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
            <div class="col-lg-3 col-sm-3 col-xs-12">
              <!-- small box -->
              <div class="small-box  bg-yellow">
                <div class="inner">
                  <h3>Mi Expediente</h3>

                  <p>Controles, Historial médico</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                @if(auth()->user()->patients->count())
                    <a href="/expedients/{{ auth()->user()->patients->first()->id }}/show" class="small-box-footer">Ir a Expediente <i class="fa fa-arrow-circle-right"></i></a>
                     <a href="/expedients/{{ auth()->user()->patients->first()->id }}/show" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
                   @else
                      <span class="label label-danger">Necesitas tener al menos un paciente para ir a su expediente</span>
                   @endif
              </div>
            </div>
            
          </div>