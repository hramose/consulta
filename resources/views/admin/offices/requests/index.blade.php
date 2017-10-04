@extends('layouts.app-admin')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Solicitudes de integración de clínicas'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <!-- <a href="{{ url('/admin/users/create') }}" class="btn btn-success">Nuevo Usuario</a> -->
                      
                <div class="box-toolsdd filters">
                <form action="/admin/offices/requests" method="GET">
                      
                      <div class="row">
                      <div class="col-xs-12 col-sm-2">
                        <div class="form-group">
                          <select class="form-control select2" style="width: 100%;" id="status" name="status" placeholder="-- Selecciona Estado --">
                             <option value=""></option>
                           
                                <option value="1" @if(isset($search['status']) && $search['status'] == "1") {{ 'selected' }} @endif >Realizadas</option>
                                <option value="0" @if(isset($search['status']) && $search['status'] == "0") {{ 'selected' }} @endif >Pendientes</option>
                           
                           
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                        
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
                      <th>Quien la Solicitó</th>
                      <th>Clínica</th>
                      <th>Dirección</th>
                      <th>Estado</th>
                      <th>Creado</th>
                    
                      <th></th>
                    </tr>
                  </thead>
                  @foreach($requestOffices as $requestOffice)
                    <tr>
                     
                      <td data-title="ID">{{ $requestOffice->id }}</td>
                     
                  
        
                      <td data-title="Quien la Solicitó">{{ $requestOffice->user->name }} - {{ $requestOffice->user->email }}</td>
                        <td data-title="Clínica" >
                        {{ $requestOffice->name }}
                         
                        </td>
                        <td data-title="Dirección" >
                        {{ $requestOffice->phone }} - {{ $requestOffice->address }}
                         
                        </td>
                      
                        <td data-title="Estatus">
                        
                        @if ($requestOffice->status)
                            
                                  <button type="submit"  class="btn btn-success btn-xs" form="form-active-inactive" formaction="{!! URL::route('requestOffices.inactive', [$requestOffice->id]) !!}">Realizado</button>
                            

                          @else
                              
                              <button type="submit"  class="btn btn-danger btn-xs " form="form-active-inactive" formaction="{!! URL::route('requestOffices.active', [$requestOffice->id]) !!}" >Pendiente</button>

                          @endif
    
                      </td>
                      <td data-title="Creado">{{ $requestOffice->created_at }}</td>
                      <td data-title="" style="padding-left: 5px;">
                       
                       
                         
                          <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                          
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/admin/offices/requests/'.$requestOffice->id) !!}"><i class="fa fa-remove"></i></button>
                         
                       
                        
                      </td>
                    </tr>
                  @endforeach
                  <tr>

                    @if ($requestOffices)
                        <td  colspan="5" class="pagination-container">{!!$requestOffices->appends(['status'=> $search['status'] ])->render()!!}</td>
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
<form method="post" id="form-active-inactive">
 {{ csrf_field() }}
</form>


@endsection
@section('scripts')

@endsection
