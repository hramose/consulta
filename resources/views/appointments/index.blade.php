@extends('layouts.app')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Consultas'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <a href="{{ url('/medic/appointments/create') }}" class="btn btn-success">Agenda</a>

                <div class="box-tools">
                  <form action="/medic/appointments" method="GET">
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
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                  <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>De</th>
                    <th>A</th>
                    <th></th>
                  </tr>
                  @foreach($appointments as $appointment)
                     
                      <tr>
                        <td>{{ $appointment->id }}</td>
                        <td><a href="{{ url('/medic/appointments/'.$appointment->id.'/edit') }}" title="{{ $appointment->patient->first_name }}">{{ $appointment->patient->first_name }}</a></td>
                        <td>{{ $appointment->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->start)->format('h:i:s A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->end)->format('h:i:s A') }}</td>
                        <td>
                          <div class="btn-group">
                            <a href="{{ url('/medic/appointments/'.$appointment->id.'/edit') }}" class="btn btn-info" title="{{ $appointment->status == 0 ? 'Iniciar Consulta' : 'Ver consulta' }}"><i class="fa fa-eye"></i></a>
                           
                            @if(!$appointment->status)
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/medic/appointments/'.$appointment->id) !!}"><i class="fa fa-remove"></i></button>
                            @endif
                          </div>
                        </td>
                      </tr>
                    
                  @endforeach
                   <tr>

                    @if ($appointments)
                        <td  colspan="7" class="pagination-container">{!!$appointments->appends(['q' => $search])->render()!!}</td>
                    @endif


                    </tr>
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
@endsection
@section('scripts')

@endsection
