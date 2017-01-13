@extends('layouts.app-patient')

@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Busqueda de Medicos'])

    
    <section class="content">
        <div class="row">
        
          <div class="col-xs-12">
            <div class="callout callout-info">
              <h4>Filtros !</h4>

              <p>Utiliza uno o varios de los filtros de abajo para realizar la busqueda más exacta!</p>
            </div>
            <div class="box">
              <div class="box-header">
                    <form method="GET" action="{{ url('/medics/search') }}" class="form-horizontal">
                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                           <div class="form-group">
                            <label for="q" class="control-label col-sm-2">Médico</label>
                            <div class="col-sm-10">
                              <input type="text" name="q" class="form-control pull-right" placeholder="Nombre del medico" value="{{ isset($search['q']) ? $search['q'] : ''}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                          <div class="form-group">
                            <label for="speciality" class="control-label col-sm-2 col-md-3">Especialidad</label>
                            <div class="col-sm-10 col-md-9">

                               <select class="form-control select2" style="width: 100%;" name="speciality" >
                                  <option value=""></option>
                                  @foreach ($specialities as $speciality)
                                    <option value="{{ $speciality->id }}" {{ isset($selectedSpeciality) ?  $selectedSpeciality == $speciality->id ? 'selected' : '' : '' }}>{{ $speciality->name }}</option>
                                  @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                          <div class="form-group">
                            <label for="province" class="control-label col-sm-2">Provincia</label>
                            <div class="col-sm-10">
                              <select class="form-control select2" style="width: 100%;" name="province" placeholder="-- Selecciona provincia --">
                                <option></option>
                                <option value="Guanacaste" {{ isset($selectedProvince) ?  $selectedProvince == "Guanacaste" ? 'selected' : '' : '' }}>Guanacaste</option>
                                <option value="San Jose" {{ isset($selectedProvince) ?  $selectedProvince == 'San Jose' ? 'selected' : '' : '' }}>San Jose</option>
                                <option value="Heredia" {{ isset($selectedProvince) ?  $selectedProvince == 'Heredia' ? 'selected' : '' : '' }}>Heredia</option>
                                <option value="Limon" {{ isset($selectedProvince) ?  $selectedProvince == 'Limon' ? 'selected' : '' : '' }}>Limon</option>
                                <option value="Cartago" {{ isset($selectedProvince) ?  $selectedProvince == 'Cartago' ? 'selected' : '' : '' }}>Cartago</option>
                                <option value="Puntarenas" {{ isset($selectedProvince) ?  $selectedProvince == 'Puntarenas' ? 'selected' : '' : '' }}>Puntarenas</option>
                                <option value="Alajuela" {{ isset($selectedProvince) ?  $selectedProvince == 'Alajuela' ? 'selected' : '' : '' }}>Alajuela</option>
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
                @if ($medics)
                    @if (!count($medics))
                      <p class="text-muted well well-sm no-shadow text-center" style="margin-top: 10px;">
                        No se encontraron elementos con esos terminos de busqueda
                      </p>
                    @else
                      <table class="table table-hover">
                        <tr>
                          
                          <th>Nombre</th>
                          <th>Lugar</th>
                          <th></th>
                        </tr>
                        @foreach($medics as $medic)
                          <tr>
                            
                            <td>Dr. {{ $medic->name }}</td>
                            <td>
                               @forelse($medic->offices as $office)
                                  <span class="label bg-yellow">{{ $office->province }} - {{ $office->city }}</span> <br/>
                               @empty
                                  <span class="label bg-default">Desconocido</span>
                               @endforelse
                            </td>
                            <td>
                              <div class="btn-group">
                                <a href="{{ url('/medics/'.$medic->id.'/schedule') }}" class="btn btn-info"><i class="fa fa-calendar"></i> Reservar cita</a>
                                <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                                <a href="{{ url('/medics/'.$medic->id.'/share') }}" class="btn btn-default"><i class="fa fa-share"></i> Enviar Dirección</a>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                        <tr>

                         
                              <td  colspan="4" class="pagination-container">{!!$medics->appends(['q' => $search['q'],'speciality' => $selectedSpeciality , 'province' => $search['province'],'lat' => $search['lat'],'lon' => $search['lon']])->render()!!}</td>
                         


                          </tr>
                      </table>
                    @endif
              @else
                   <p class="text-muted well well-sm no-shadow text-center" style="margin-top: 10px;">
                      Realiza una busqueda para mostrar elementos
                    </p>
              @endif
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
  <script type="text/javascript">
      
      

  </script>
@endsection
