@extends('layouts.app-admin')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Calificaciones de App Movil'])


    <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                <!-- <a href="{{ url('/admin/users/create') }}" class="btn btn-success">Nuevo Usuario</a> -->
                      
                <div class="box-toolsdd filters">
                  <div class="reviewFinal" >
                      <b>Calificación General: </b>
                     @for ($i=1; $i <= 5 ; $i++)
                        <span class="fa fa-star{!! ($i <= $rating_app_cache) ? '' : '-o'!!}" style="color: #00a65a;"></span>
                    @endfor 
                  </div>
                </div>
              </div>
              <!-- /.box-header -->
             
              <div class="box-body table-responsive no-padding" id="no-more-tables">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <!-- <th>Tipo App</th> -->
                      <th>Comentario</th>
                      <th>Rating (1-5)</th>
                      <th>Creado</th>
                    
                      <th></th>
                    </tr>
                  </thead>
                  @foreach($reviews as $review)
                    <tr>
                     
                      <td data-title="ID">{{ $review->id }}</td>
                     
                    
                     <!--  <td data-title="Tipo App">{{ $review->app }} </td> -->
        
                      <td data-title="Comentario">{{ $review->comment }}</td>
                        <td data-title="Rating" style="color: #f39c12;">
                         
                          @for ($i=1; $i <= 5; $i++)
                            <span class="fa fa-star{!! ($i <= $review->rating) ? '' : '-o'!!}"></span>
                          @endfor 
                        </td>
                      
                      <td data-title="Creado">{{ $review->created_at }}</td>
                      <td data-title="" style="padding-left: 5px;">
                       
                       
                         
                          <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                          
                            <button type="submit" class="btn btn-danger" form="form-delete" formaction="{!! url('/admin/reviews/'.$review->id) !!}"><i class="fa fa-remove"></i></button>
                         
                       
                        
                      </td>
                    </tr>
                  @endforeach
                  <tr>

                    @if ($reviews)
                        <td  colspan="5" class="pagination-container">{!!$reviews->render()!!}</td>
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
