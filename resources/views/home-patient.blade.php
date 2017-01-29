@extends('layouts.app-patient')

@section('content')
    


      <h1>
         Bienvenido
        <small>{{ auth()->user()->name }}</small>
      </h1>
      <h3>¿Que deseas hacer?</h3>
     <section class="content">
        <div class="row">

          <div class="row">
              <div class=" col-xs-12 col-sm-6">
                <!-- small box -->
                <div class="small-box bg-aqua box-search-medic" style="position: relative;">
                  <div class="inner">
                    <h3>Buscar Médico</h3>

                    <p>General o Especialista</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-user-md"></i>
                  </div>
                  <a href="/medics/search" class="small-box-footer bg-green footer-general">General <i class="fa fa-arrow-circle-right"></i></a>
                  <a href="/medics/search?specialist=1" class="small-box-footer bg-yellow footer-specialist">Especialista <i class="fa fa-arrow-circle-right"></i></a>

                  <!-- <a href="/medics/search?specialist=1" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a> -->
                </div>
              </div>
              <!-- ./col -->
              <div class=" col-xs-12 col-sm-6">
                <!-- small box -->
                <div class="small-box bg-green" style="position: relative;">
                  <div class="inner">
                    <h3>Buscar clinica</h3>

                    <p>Hospital</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-hospital-o"></i>
                  </div>
                  <a href="/clinics/search" class="small-box-footer">Iniciar <i class="fa fa-arrow-circle-right"></i></a>
                   <a href="/clinics/search" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
                </div>
              </div>
              <!-- ./col -->
              
            </div>

        </div>

    </section>


@endsection
