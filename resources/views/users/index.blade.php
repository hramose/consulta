@extends('layouts.app-admin')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Usuarios'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <!-- <a href="{{ url('/admin/users/create') }}" class="btn btn-success">Nuevo Usuario</a> -->
                      
                <div class="box-toolsdd">
                  <form action="/admin/users" method="GET">
                      
                      <div class="row">
                      <div class="col-xs-12 col-sm-2">
                        <div class="form-group">
                          <select class="form-control select2" style="width: 100%;" name="role" placeholder="-- Selecciona Tipo --">
                             <option value=""></option>
                            @foreach($roles as $role)
                                <option value="{{$role->name}}" @if(isset($search['role']) && $search['role'] == $role->name) {{ 'selected' }} @endif > {{ $role->name }}</option>
                            @endforeach
                           
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-3">
                        <div class="input-group input-group-sm" >
                      
                            
                            <input type="text" name="q" class="form-control pull-right" placeholder="Buscar por nombre..." value="{{ isset($search) ? $search['q'] : '' }}">
                            <div class="input-group-btn">

                              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                          
                          
                        </div>
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
                      <th>Email</th>
                      <th>Rol(tipo)</th>
                      <th>Estatus</th>
                      <th></th>
                    </tr>
                  </thead>
                  @foreach($users as $user)
                    <tr>
                     
                      <td data-title="ID">{{ $user->id }}</td>
                     
                    
                      <td data-title="Nombre">{{ $user->name }} </td>
        
                      <td data-title="Email">{{ $user->email }}</td>
                      <td data-title="Email">{{ $user->roles->first()->name }}</td>
                      <td data-title="Estatus">
                          
                         @if ($user->active)
                             
                                  <button type="submit"  class="btn btn-success btn-xs" form="form-active-inactive" formaction="{!! URL::route('users.inactive', [$user->id]) !!}">Active</button>
                             

                          @else
                              
                              <button type="submit"  class="btn btn-danger btn-xs " form="form-active-inactive" formaction="{!! URL::route('users.active', [$user->id]) !!}" >Inactive</button>

                          @endif
    
                      </td>
                      <td data-title="" style="padding-left: 5px;">
                       
                       
                         
                          <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                          
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/admin/users/'.$user->id) !!}"><i class="fa fa-remove"></i></button>
                         
                       
                        
                      </td>
                    </tr>
                  @endforeach
                  <tr>

                    @if ($users)
                        <td  colspan="6" class="pagination-container">{!!$users->appends(['q' => $search['q'],'role'=> $search['role'] ])->render()!!}</td>
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

<!-- <form method="post" id="form-addToYourPatients" data-confirm="Estas Seguro?">
  {{ csrf_field() }}
</form> -->
@endsection
@section('scripts')

@endsection
