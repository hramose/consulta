@extends('layouts.app-clinic')

@section('content')
    
    @include('layouts/partials/header-pages',['page'=>'Panel de control'])


     <section class="content">
        <div class="row boxes-home" >
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>Agenda</h3>

                  <p>Calendario</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="/clinic/appointments" class="small-box-footer">Ir a agenda <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/clinic/appointments" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>Medicos</h3>

                  <p>Doctores</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="/clinic/medics" class="small-box-footer">Ir a Medicos <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/clinic/medics" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>Pacientes</h3>

                  <p>Expediente Clínico</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="/clinic/patients" class="small-box-footer">Ir a Pacientes <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/clinic/patients" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>Reportes</h3>

                  <p>Información</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="/clinic/reports" class="small-box-footer">Ir a reportes <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/clinic/reports" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            
          </div>

  

    </section>


@endsection
