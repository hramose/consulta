@extends('layouts.app-patient')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Busqueda de Clinicas o Hospitales'])

    
    <section class="content">
        <div class="row">
        
          <div class="col-xs-12">
            <div class="callout callout-info">
              <h4>Filtros !</h4>

              <p>Utiliza uno o varios de los filtros de abajo para realizar la busqueda más exacta!</p>
            </div>
            <div class="box">
              <div class="box-header">
                    <form method="GET" action="{{ url('/clinics/search') }}" class="form-horizontal">
                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                           <div class="form-group">
                            <label for="q" class="control-label col-sm-2">Clínica</label>
                            <div class="col-sm-10">
                              <input type="text" name="q" class="form-control pull-right" placeholder="Nombre de la clinica u hospital" value="{{ isset($search['q']) ? $search['q'] : ''}}">
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                          <div class="form-group">
                            <label for="province" class="control-label col-sm-2 col-md-3">Provincia</label>
                            <div class="col-sm-10 col-md-9">
                              <select class="form-control select2" style="width: 100%;" name="province" placeholder="-- Selecciona provincia --">
                                <option></option>
                                <option value="Guanacaste" {{ isset($search['province']) ?  $search['province'] == "Guanacaste" ? 'selected' : '' : '' }}>Guanacaste</option>
                                <option value="San Jose" {{ isset($search['province']) ?  $search['province'] == 'San Jose' ? 'selected' : '' : '' }}>San Jose</option>
                                <option value="Heredia" {{ isset($search['province']) ?  $search['province'] == 'Heredia' ? 'selected' : '' : '' }}>Heredia</option>
                                <option value="Limon" {{ isset($search['province']) ?  $search['province'] == 'Limon' ? 'selected' : '' : '' }}>Limon</option>
                                <option value="Cartago" {{ isset($search['province']) ?  $search['province'] == 'Cartago' ? 'selected' : '' : '' }}>Cartago</option>
                                <option value="Puntarenas" {{ isset($search['province']) ?  $search['province'] == 'Puntarenas' ? 'selected' : '' : '' }}>Puntarenas</option>
                                <option value="Alajuela" {{ isset($search['province']) ?  $search['province'] == 'Alajuela' ? 'selected' : '' : '' }}>Alajuela</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                           
                                
                                <div class="row">
                                  <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
                                      <div class="form-group">
                                         <label for="" class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-6">Cerca de aquí</label>
                                        <div class="col-sm-6">
                                            <button type="button" class="btn btn-default btn-geo"><i class="fa fa-"></i>Tu ubicación</button>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="col-xs-12 col-sm-3 col-lg-3">
                                    <div class="form-group">
                                         <div class="col-sm-12">
                                            <div class="input-group">
                                                  <span class="input-group-addon">
                                                    lat
                                                  </span>
                                              <input type="text" class="form-control" name="lat" value="{{ isset($search['lat']) ? $search['lat'] : ''}}">
                                            </div>
                                          </div>
                                      </div>
                                    <!-- /input-group -->
                                  </div>
                                  <!-- /.col-lg-6 -->
                                  <div class="col-xs-12 col-sm-3 col-lg-3">
                                    <div class="form-group">
                                         <div class="col-sm-12">
                                            <div class="input-group">
                                                  <span class="input-group-addon">
                                                    lon
                                                  </span>
                                              <input type="text" class="form-control" name="lon" value="{{ isset($search['lon']) ? $search['lon'] : ''}}">
                                            </div>
                                          </div>
                                      </div>
                                    <!-- /input-group -->
                                  </div>
                                  <!-- /.col-lg-6 -->
                                </div>
                                      
                            </div>
                          
                        </div>
                         
                      
                       <div class="form-group">
                            <div class="col-sm-12">
                              <button type="submit" class="btn btn-success btn-search" style="width: 100%;margin-top: 1rem;"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                   </form>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                  <tr>
                    
                    <th>Nombre</th>
                    <th>Lugar</th>
                    <th></th>
                  </tr>
                  @foreach($clinics as $clinic)
                    <tr>
                     
                      <td>{{ $clinic->name }}</td>
                      <td>
                         <span class="label bg-yellow">{{ $clinic->province }} - {{ $clinic->city }}</span>

                      </td>
                      <td>
                        <div class="btn-group">
                          <a href="#" class="btn btn-info"><i class="fa fa-address"></i> Consultar ubicación</a>
                          <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                          <a href="tel:26665859" class="btn btn-default"><i class="fa fa-share"></i> Llamar</a>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                  <tr>

                    @if ($clinics)
                        <td  colspan="3" class="pagination-container">{!!$clinics->appends(['q' => $search['q'], 'province' => $search['province'],'lat' => $search['lat'],'lon' => $search['lon']])->render()!!}</td>
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


@endsection
@section('scripts')
  <script>
    $(function () {
    
      function obtainGeolocation(){
       //obtener la posición actual y llamar a la función  "localitation" cuando tiene éxito
       window.navigator.geolocation.getCurrentPosition(localitation);
       }
       function localitation(geo){
       
      // En consola nos devuelve el Geoposition object con los datos nuestros
          
          $('input[name="lat"]').val(geo.coords.latitude);
          $('input[name="lon"]').val(geo.coords.longitude);
          
       }
       //llamando la funcion inicial para ver trabajar la API
       

       $('.btn-geo').on('click', function (e) {
           obtainGeolocation();
       })
    });
</script>
  
@endsection
