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
              <div class=" col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua" style="position: relative;">
                  <div class="inner">
                    <h3>Buscar un Médico</h3>

                    <p>General o Especialista</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-user-md"></i>
                  </div>
                  <a href="#" class="small-box-footer">Iniciar <i class="fa fa-arrow-circle-right"></i></a>
                  <a href="#" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
                </div>
              </div>
              <!-- ./col -->
              <div class=" col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green" style="position: relative;">
                  <div class="inner">
                    <h3>Buscar clinica</h3>

                    <p>Hospital</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-hospital-o"></i>
                  </div>
                  <a href="#" class="small-box-footer">Iniciar <i class="fa fa-arrow-circle-right"></i></a>
                   <a href="#" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
                </div>
              </div>
              <!-- ./col -->
              
            </div>

        </div>

    </section>


@endsection
