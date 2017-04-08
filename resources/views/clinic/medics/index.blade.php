@extends('layouts.app-clinic')

@section('css')

@endsection
@section('content')
     <div id="infoBox" class="alert"></div>
 
        @include('layouts/partials/header-pages',['page'=>'Medicos'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <!-- <a href="{{ url('/clinic/medics/create') }}" class="btn btn-success">Nuevo medico</a> -->

                <div class="box-tools">
                  <form action="/clinic/medics" method="GET">
                    <div class="input-group input-group-sm" style="width: 150px;">
                      
                        
                        <input type="text" name="q" class="form-control pull-right" placeholder="Buscar..." value="{{ isset($search) ? $search['q'] : '' }}">
                        <div class="input-group-btn">

                          <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                      
                      
                    </div>
                  </form>
                </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding" id="no-more-tables">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Email</th>
                      <th>Especialidades</th>
                      <th></th>
                    </tr>
                  </thead>
                  @foreach($medics as $medic)
                    <tr>
                     
                      <td data-title="ID">{{ $medic->id }}</td>
                      <td data-title="Nombre">
                     
                        <a href="{{ url('/clinic/medics/'.$medic->id.'/edit') }}" title="{{ $medic->name }}">{{ $medic->name }}</a>
                     
                      </td>
                      <td data-title="Teléfono">{{ $medic->phone }}</td>
                      <td data-title="Email">{{ $medic->email }}</td>
                     <td data-title="Especialidades"> 
                       @foreach($medic->specialities as $speciality)
                            <span class="btn btn-warning btn-xs">{{ $speciality->name }}</span>
                       @endforeach

                       </td>
                      <td data-title="" style="padding-left: 5px;">
                       
                        <div class="btn-group">
                          <!-- <a href="{{ url('/clinic/medics/'.$medic->id.'/edit') }}" class="btn btn-info" title="Editar Paciente"><i class="fa fa-edit"></i></a> -->
                         
                           @if(!$medic->appointments->count())
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/clinic/medics/'.$medic->id) !!}" title="Eliminar Paciente"><i class="fa fa-remove"></i></button>
                          @endif
                        </div>
                        
                       
                      </td>
                    </tr>
                  @endforeach
                    @if ($medics)
                        <td  colspan="5" class="pagination-container">{!!$medics->appends(['q' => $search['q']])->render()!!}</td>
                    @endif
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>

    </section>



<form method="post" id="form-delete" data-confirm="Estas Seguro?">
  <input name="_method" type="hidden" value="DELETE">{{ csrf_field() }}
</form>
<!-- <form method="post" id="form-addToYourPatients" data-confirm="Estas Seguro?">
  {{ csrf_field() }}
</form> -->
@endsection
@section('scripts')

@endsection
