@extends('layouts.app-assistant')

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
                <!-- <a href="{{ url('/assistant/medics/create') }}" class="btn btn-success">Nuevo medico</a> -->

                <div class="box-tools">
                  <form action="/assistant/medics" method="GET">
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
                        
                        {{ $medic->name }}
                        @if(!$medic->verifyOffice($office->id))
                            <button type="submit"  class="btn btn-danger btn-xs" form="form-active-inactive" formaction="/assistant/medics/{{$medic->id }}/offices/{{ $office->id }}/assign">Pendiente de confirmación</button>
                          @endif
                     
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


<form method="post" id="form-active-inactive">
 {{ csrf_field() }}
</form>
<form method="post" id="form-delete" data-confirm="Estas Seguro?">
  <input name="_method" type="hidden" value="DELETE">{{ csrf_field() }}
</form>
<!-- <form method="post" id="form-addToYourPatients" data-confirm="Estas Seguro?">
  {{ csrf_field() }}
</form> -->
@endsection
@section('scripts')

@endsection
