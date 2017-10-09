@extends('layouts.app-clinic')

@section('css')
 <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
<link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 
@endsection
@section('content')
     <div id="infoBox" class="alert"></div>
 
        @include('layouts/partials/header-pages',['page'=>'Pacientes'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <a href="{{ url('/clinic/patients/create') }}" class="btn btn-success">Nuevo paciente</a>

                <div class="box-tools">
                  <form action="/clinic/patients" method="GET">
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
                      <th>Dirección</th>
                      <th></th>
                    </tr>
                  </thead>
                  @foreach($patients as $patient)
                    <tr>
                      
                      <td data-title="ID">{{ $patient->id }}</td>
                     
                      <td data-title="Nombre">
                      
                        <a href="{{ url('/clinic/patients/'.$patient->id.'/edit') }}" title="{{ $patient->first_name }}">{{ $patient->first_name }} {{ $patient->last_name }}</a>
                     
                      </td>
                      <td data-title="Teléfono">{{ $patient->phone }}</td>
                      <td data-title="Email">{{ $patient->email }}</td>
                      <td data-title="Dirección">{{ $patient->address }}</td>
                      <td data-title="" style="padding-left: 5px;">
                        
                        <div class="btn-group">
                          <a href="{{ url('/clinic/patients/'.$patient->id.'/edit') }}" class="btn btn-info" title="Editar Paciente"><i class="fa fa-edit"></i></a>
                          <a href="{{ url('/clinic/patients/'.$patient->id.'/invoices') }}" class="btn btn-success" title="Ver Facturado"><i class="fa fa-money"></i> Historial facturación</a>
                          <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                           @if(!$patient->appointments->count() && $patient->isPatientOf(auth()->user()))
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/clinic/patients/'.$patient->id) !!}" title="Eliminar Paciente"><i class="fa fa-remove"></i></button>
                          @endif
                        </div>
                       
                       
                      </td>
                    </tr>
                  @endforeach
                    @if ($patients)
                        <td  colspan="6" class="pagination-container">{!!$patients->appends(['q' => $search['q']])->render()!!}</td>
                    @endif
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>

    </section>

@include('medic/patients/partials/initAppointment')

<form method="post" id="form-delete" data-confirm="Estas Seguro?">
  <input name="_method" type="hidden" value="DELETE">{{ csrf_field() }}
</form>
<!-- <form method="post" id="form-addToYourPatients" data-confirm="Estas Seguro?">
  {{ csrf_field() }}
</form> -->
@endsection
@section('scripts')
  <script src="/js/plugins/select2/select2.full.min.js"></script>  
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/plugins/moment/moment.min.js"></script>
  <script src="/js/plugins/moment/locale/es.js"></script>
  <script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
  <script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script> 
  <script src="{{ elixir('/js/clinic.patients.min.js') }}"></script>
@endsection
