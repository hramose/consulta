@extends('layouts.app')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Pacientes'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <a href="{{ url('/medic/patients/create') }}" class="btn btn-success">Nuevo paciente</a>

                <div class="box-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                    <div class="input-group-btn">
                      <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>email</th>
                    <th>address</th>
                    <th></th>
                  </tr>
                  @foreach($patients as $patient)
                    <tr>
                      <td>{{ $patient->id }}</td>
                      <td><a href="{{ url('/medic/patients/'.$patient->id.'/edit') }}" title="{{ $patient->first_name }}">{{ $patient->first_name }} {{ $patient->last_name }}</a></td>
                      <td>{{ $patient->phone }}</td>
                      <td>{{ $patient->email }}</td>
                      <td>{{ $patient->address }}</td>
                      <td>
                        <div class="btn-group">
                          <a href="{{ url('/medic/patients/'.$patient->id.'/edit') }}" class="btn btn-info"><i class="fa fa-edit"></i></a>
                          <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                           @if(!$patient->appointments->count())
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/medic/patients/'.$patient->id) !!}"><i class="fa fa-remove"></i></button>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
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
