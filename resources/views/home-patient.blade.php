@extends('layouts.app-patient')

@section('content')
    


      <h1>
         Bienvenido
        <small>{{ auth()->user()->name }}</small>
      </h1>
      <h3>¿Que deseas hacer?</h3>
      @if(auth()->user()->active)
      <section class="content">
        <div class="row">

          <div class="row">
              <div class=" col-xs-12 col-sm-12 col-md-4">
                <!-- small box -->
                <div class="small-box bg-aqua box-search-medic" style="position: relative;">
                  <div class="inner">
                   <h3>Buscar Medico</h3>
                    <div class="row">
                      <div class=" col-xs-12 col-sm-4">
                        <div class="small-box bg-white">
                          <div class="inner">
                            <span>Por Nombre </span>
                            <a href="/medics/search"></a>
                          </div>
                          
                        </div>
                      </div>
                      <div class=" col-xs-12 col-sm-4">
                        <div class="small-box bg-white">
                          <div class="inner">
                            <span>General </span>
                            <a href="/medics/search?general=1"></a>
                          </div>
                        </div>
                      </div>
                      <div class=" col-xs-12 col-sm-4">
                        <div class="small-box bg-blank">
                          <div class="inner">
                            <span>Especialista</span>
                            <a href="/medics/search?specialist=1"></a>
                          </div>
                        </div>
                      </div>
                    </div>
                     
                  </div>
                  <div class="icon">
                    <i class="fa fa-user-md"></i>
                  </div>
                  
                  

                  <!-- <a href="/medics/search?specialist=1" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a> -->
                </div>
              </div>
              <!-- ./col -->
              <div class=" col-xs-12 col-sm-12 col-md-4">
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
              <div class=" col-xs-12 col-sm-12 col-md-4">
                <!-- small box -->
                <div class="small-box bg-yellow" style="position: relative;">
                  <div class="inner">
                    <h3>Mi expediente</h3>

                    <p>Controles, Historial médico, Medicamentos</p>
                   
                  </div>
                  <div class="icon">
                    <i class="fa fa-edit"></i>
                  </div>
                  @if(auth()->user()->patients)
                    <a href="/expedients/{{ auth()->user()->patients->first()->id }}/show" class="small-box-footer">Iniciar <i class="fa fa-arrow-circle-right"></i></a>
                     <a href="/expedients/{{ auth()->user()->patients->first()->id }}/show" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
                   @else
                      <p>Necesitas tener al menos un paciente para ir a su expediente</p>
                   @endif
                </div>
              </div>
              <!-- ./col -->
              
            </div>

        </div>

    </section>
    @endif


@endsection
