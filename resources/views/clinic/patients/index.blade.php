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
                      @if($patient->isPatientOf(auth()->user()))
                      <td data-title="ID">{{ $patient->id }}</td>
                      @else
                        <td data-title="ID"></td>
                      @endif
                      <td data-title="Nombre">
                      @if($patient->isPatientOf(auth()->user()))
                        <a href="{{ url('/clinic/patients/'.$patient->id.'/edit') }}" title="{{ $patient->first_name }}">{{ $patient->first_name }} {{ $patient->last_name }}</a>
                      @else
                          {{ $patient->first_name }} {{ $patient->last_name }}
                      @endif
                      </td>
                      <td data-title="Teléfono">{{ $patient->phone }}</td>
                      <td data-title="Email">{{ $patient->email }}</td>
                      <td data-title="Dirección">{{ $patient->address }}</td>
                      <td data-title="" style="padding-left: 5px;">
                        @if($patient->isPatientOf(auth()->user()))
                        <div class="btn-group">
                          <a href="{{ url('/clinic/patients/'.$patient->id.'/edit') }}" class="btn btn-info" title="Editar Paciente"><i class="fa fa-edit"></i></a>
                          <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                           @if(!$patient->appointments->count())
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/clinic/patients/'.$patient->id) !!}" title="Eliminar Paciente"><i class="fa fa-remove"></i></button>
                          @endif
                        </div>
                        @else
                          <form action="{!! url('/clinic/patients/'.$patient->id.'/add') !!}" method="post" id="form-addToYourPatients" data-confirm="Estas Seguro?" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="input-group">
                              <div class="input-group-btn">
                                <button type="submit" class="btn btn-danger"><i class="fa fa-plus"></i> Agregar a tu lista</button>
                              </div>
                              <!-- /btn-group -->
                              <input type="text" name="id_patient_confirm" placeholder="ID de confirmación" class="form-control" required="required" />
                            </div>
                             
                          </form>
                          

                        @endif
                       
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

@include('patients/partials/initAppointment')

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/locale/es.js"></script>
  <script src="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
  <script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script> 
  <script src="{{ elixir('/js/clinic.patients.min.js') }}"></script>
@endsection
