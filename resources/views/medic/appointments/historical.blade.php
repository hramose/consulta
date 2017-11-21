@extends('layouts.app')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Historico de consultas'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                
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
              <div class="box-body table-responsive no-padding" id="no-more-tables">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Paciente</th>
                      <th>Motivo</th>
                      <th>Fecha</th>
                      <th>De</th>
                      <th>A</th>
                      <th></th>
                    </tr>
                  </thead>
                  
                  @foreach($appointments as $appointment)
                     
                      <tr>
                        <td data-title="ID">{{ $appointment->id }}</td>
                        <td data-title="Paciente">
                          @if($appointment->status == 2)
                            {{ ($appointment->patient) ? $appointment->patient->first_name : 'Paciente Eliminado' }}
                          @else
                            <a href="{{ url('/medic/appointments/'.$appointment->id.'/edit') }}" title="{{ ($appointment->patient) ? $appointment->patient->first_name : 'Paciente Eliminado' }}">{{ ($appointment->patient) ? $appointment->patient->first_name : 'Paciente Eliminado' }}</a>
                          @endif
                        </td>
                        <td data-title="Motivo">{{ $appointment->title }}</td>
                        <td data-title="Fecha">{{ \Carbon\Carbon::parse($appointment->date)->toDateString() }}</td>
                        <td data-title="De">{{ \Carbon\Carbon::parse($appointment->start)->format('h:i:s A') }}</td>
                        <td data-title="a">{{ \Carbon\Carbon::parse($appointment->end)->format('h:i:s A') }}</td>
                        <td data-title="" style="padding-left: 5px;">
                          <div class="btn-group">
                            @if($appointment->status == 2)
                              <span class="label label-warning">Paciente No Asistió a la cita</span>
                            @else
                              @if($appointment->status == 0 && \Carbon\Carbon::now()->ToDateString() > $appointment->date)
                                <span class="label label-default" style="margin-left: 5px;margin-top: 8px;display: inline-block;">Cita perdida</span>
                              @else
                                <a href="{{ url('/medic/appointments/'.$appointment->id.'/edit') }}" class="btn btn-info" title="{{ $appointment->status == 0 ? 'Iniciar Consulta' : 'Ver consulta' }}"><i class="fa fa-eye"></i> {{ $appointment->status == 0 ? 'Iniciar Consulta' : 'Ver consulta' }}</a>
                              @endif
                             @if($appointment->status != 2 && $appointment->status != 1)
                                <button type="submit" class="btn btn-warning" form="form-noshows" formaction="{!! url('/medic/appointments/'.$appointment->id.'/noshows') !!}">No Asistió</button>
                              @endif
                              @if(!$appointment->status)
                                <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/medic/appointments/'.$appointment->id) !!}"><i class="fa fa-remove"></i></button>
                              @endif

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
<form method="post" id="form-noshows" data-confirm="Estas Seguro?">
  <input name="_method" type="hidden" value="PUT">{{ csrf_field() }}
</form>
@endsection
@section('scripts')

@endsection
