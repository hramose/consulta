@extends('layouts.app')

@section('content')
    
    @include('layouts/partials/header-pages',['page'=>'Panel de control'])


     <section class="content">

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
                              
                               <img class="profile-user-img img-responsive img-circle" src="{{ getAvatar($appointment->patient) }}" alt="User profile picture">
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
