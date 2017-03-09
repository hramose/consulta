@extends('layouts.app-admin')

@section('content')
     @include('layouts/partials/header-pages',['page'=>'Panel de control'])


    <section class="content">
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>Usuarios</h3>

                  <p>Medicos o pacientes registrados</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="/admin/users/" class="small-box-footer">Ir a usuarios <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/admin/users/" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            
          </div>


    </section>

@endsection
