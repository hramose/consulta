@extends('layouts.app')

@section('content')
    
    @include('layouts/partials/header-pages',['page'=>'Panel de control'])


     <section class="content">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>Agenda</h3>

                  <p>Ver agenda</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="/medic/appointments/create" class="small-box-footer">Ir a agenda <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/medic/appointments/create" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
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

                  <p>Modifique la información de su consultorio</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="/medic/account/edit" class="small-box-footer">Ir a consultorios <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/medic/account/edit" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-sm-6 col-xs-12">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>Expedientes</h3>

                  <p>Ir a Expediente Clínico de pacientes</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="/medic/patients" class="small-box-footer">Ir a expedientes <i class="fa fa-arrow-circle-right"></i></a>
                <a href="/medic/patients" style="position: absolute;left:0;right: 0;top:0; bottom: 0;"></a>
              </div>
            </div>
            <!-- ./col -->
          </div>

        <div class="row">


          <div class="col-xs-12">
            
                <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Citas para Hoy</h3>

                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <ul class="products-list product-list-in-box">
                       @foreach($appointments as $appointment)
                          
                          <li class="item">
                            <div class="product-img">
                              <img class="profile-user-img img-responsive img-circle" src="{{ (Storage::disk('public')->exists('patients/'.$appointment->patient->id.'/photo.jpg')) ? Storage::url('patients/'.$appointment->patient->id.'/photo.jpg') : Storage::url('avatars/default-avatar.jpg') }}" alt="User profile picture">
                            </div>
                            <div class="product-info">
                              <a href="{{ url('/medic/appointments/'.$appointment->id.'/edit') }}" class="product-title"> {{ $appointment->title }}
                                <span class="label label-warning pull-right">{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}</span></a>
                                  <span class="product-description">
                                   {{ $appointment->patient->first_name }} <span class="label label-success">{{ \Carbon\Carbon::parse($appointment->start)->format('h:i:s A') }} a {{ \Carbon\Carbon::parse($appointment->end)->format('h:i:s A') }}</span>
                                  </span>
                            </div>
                          </li>
                          
                       @endforeach
                    </ul>
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer text-center">
                    <a href="{{ url('/medic/appointments') }}" class="uppercase">Ver todas las citas</a>
                  </div>
                  <!-- /.box-footer -->
                </div>

            </div>

        </div>

    </section>

<form method="post" id="form-delete" data-confirm="Estas Seguro?">
  <input name="_method" type="hidden" value="DELETE">{{ csrf_field() }}
</form>

@endsection
