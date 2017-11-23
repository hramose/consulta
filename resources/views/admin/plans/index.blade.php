@extends('layouts.app-admin')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Planes de Subscripción'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                
                <a href="{{ url('/admin/plans/create') }}" class="btn btn-success">Nuevo Plan</a>      
                <div class="box-toolsdd filters">
                  <form action="/admin/plans" method="GET">
                      
                      <div class="row">
                      
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
                      <th>Costo</th>
                      <th>Periodo (Meses)</th>
                      <th></th>
                    </tr>
                  </thead>
                  @foreach($plans as $plan)
                    <tr>
                     
                      <td data-title="ID">{{ $plan->id }}</td>
                     
                    
                      <td data-title="Nombre">{{ $plan->title }} </td>
        
                      <td data-title="Costo">{{ money($plan->cost, '$') }}</td>
                      <td data-title="Periodo">
                      {{ $plan->quantity }} 
                      </td>
                     
                     
                      <td data-title="" style="padding-left: 5px;">
                       
                       
                         
                      <a href="{{ url('/admin/plans/'.$plan->id.'/edit') }}" class="btn btn-info" title="Editar"><i class="fa fa-edit"></i> Editar</a>
                          
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/admin/plans/'.$plan->id) !!}"><i class="fa fa-remove"></i></button>
                         
                       
                        
                      </td>
                    </tr>
                  @endforeach
                  <tr>

                    @if ($plans)
                        <td  colspan="5" class="pagination-container">{!!$plans->appends(['q' => $search['q'] ])->render()!!}</td>
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


<!-- <form method="post" id="form-addToYourPatients" data-confirm="Estas Seguro?">
  {{ csrf_field() }}
</form> -->
@endsection
@section('scripts')

@endsection
