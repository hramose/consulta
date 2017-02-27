@extends('layouts.app-patient')
@section('css')
  <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.css" />
  <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials-theme-flat.css" />
@endsection
@section('content')
     
     @include('layouts/partials/header-pages',['page'=>'Busqueda de Clinicas o Hospitales'])

    
    <section class="content">
        <div class="row">
        
          <div class="col-xs-12">
            <div class="callout callout-info callout-search">
              <button type="button" class="close" data-dismiss="callout" aria-hidden="true">×</button>
              <h4>Filtros !</h4>

              <p>Utiliza una o varias caracteristicas de busqueda de abajo para realizar la busqueda más exacta!</p>
            </div>
            <div class="box">
              <div class="box-header">
                    <form method="GET" action="{{ url('/clinics/search') }}" class="form-horizontal">
                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                           <div class="form-group">
                            <label for="q" class="control-label col-sm-2">Clínica</label>
                            <div class="col-sm-10">
                              <input type="text" name="q" class="form-control pull-right" placeholder="Nombre de la clinica u hospital" value="{{ isset($search['q']) ? $search['q'] : ''}}">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="box box-default collapsed-box box-search-filters box-solid">
                              <div class="box-header with-border ">
                                <h3 class="box-title">Caracteristicas de busqueda</h3>

                                <div class="box-tools">
                                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                  </button>
                                </div>
                                <!-- /.box-tools -->
                              </div>
                              <!-- /.box-header -->
                              <div class="box-body" style="display: none;">
                                    <div class="row">
                                      
                                    
                                      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        
                                           <div class="row">
                                              <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                 <div class="form-group">
                                                     <label for="" class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-6">Provincia</label>
                                                    <div class="col-sm-12">
                                                    <select class="form-control select2" style="width: 100%;" name="province" placeholder="-- Selecciona provincia --">
                                                      <option></option>
                                                      <option value="Guanacaste" {{ isset($search['province']) ?  $search['province'] == "Guanacaste" ? 'selected' : '' : '' }}>Guanacaste</option>
                                                      <option value="San Jose" {{ isset($search['province']) ?  $search['province'] == "San Jose" ? "selected" : "" : "" }}>San Jose</option>
                                                      <option value="Heredia" {{ isset($search['province']) ?  $search['province'] == 'Heredia' ? 'selected' : '' : '' }}>Heredia</option>
                                                      <option value="Limon" {{ isset($search['province']) ?  $search['province'] == 'Limon' ? 'selected' : '' : '' }}>Limon</option>
                                                      <option value="Cartago" {{ isset($search['province']) ?  $search['province'] == 'Cartago' ? 'selected' : '' : '' }}>Cartago</option>
                                                      <option value="Puntarenas" {{ isset($search['province']) ?  $search['province'] == 'Puntarenas' ? 'selected' : '' : '' }}>Puntarenas</option>
                                                      <option value="Alajuela" {{ isset($search['province']) ?  $search['province'] == 'Alajuela' ? 'selected' : '' : '' }}>Alajuela</option>
                                                    </select>
                                                     
                                                    </div>
                                                </div>
                                                  
                                                 
                                              </div>
                                              <div class="col-xs-12 col-sm-4 col-lg-4">
                                                 <div class="form-group">
                                                      <label for="canton" class="control-label col-sm-2">Canton</label>
                                                     <div class="col-sm-12">
                                                        <select class="form-control select2" style="width: 100%;" name="canton" placeholder="-- Selecciona canton --">
                                                          <option></option>
                                                          
                                                        </select>
                                                        <input type="hidden" name="selectedCanton" value="{{ isset($search['canton']) ? $search['canton'] : '' }}">
                                                      </div>
                                                  </div>
                                                <!-- /input-group -->
                                              </div>
                                              <!-- /.col-lg-6 -->
                                              <div class="col-xs-12 col-sm-4 col-lg-4">
                                                 <div class="form-group">
                                                    <label for="district" class="control-label col-sm-2">Distrito</label>
                                                     <div class="col-sm-12">
                                                        <select class="form-control select2" style="width: 100%;" name="district" placeholder="-- Selecciona canton --">
                                                          <option></option>
                                                          
                                                        </select>
                                                        <input type="hidden" name="selectedDistrict" value="{{ isset($search['district']) ? $search['district'] : '' }}">
                                                      </div>
                                                  </div>
                                               
                                                <!-- /input-group -->
                                              </div>
                                              <!-- /.col-lg-6 -->
                                            </div>
                                        </div>
                                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                       <label for="" class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5">Cerca de aquí</label>
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

                              </div>
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
              <div class="box-body table-responsive no-padding" id="no-more-tables">
                 @if ($clinics)
                    @if (!count($clinics))
                      <p class="bg-red disabled text-muted well well-sm no-shadow text-center " style="margin-top: 10px;">
                        No se encontraron elementos con esos terminos de busqueda
                      </p>
                    @else
                      <table class="table table-hover">
                        <thead>
                            <tr>
                          
                              <th>Nombre</th>
                              <th>Lugar</th>
                               @if(isset($search['lat']) && $search['lat'] != '')
                              <th>Distancia</th>
                              @endif
                              <th></th>
                            </tr>
                        </thead>
                        
                        @foreach($clinics as $clinic)
                          <tr>
                           
                            <td data-title="Nombre">{{ $clinic->name }}</td>
                            <td data-title="Lugar">
                               {{ $clinic->province }}, {{ $clinic->canton }}. {{ $clinic->address }} 

                            </td>
                            @if(isset($search['lat']) && $search['lat'] != '')
                            <td data-title="Distancia">
                                 Aprox. {{ number_format($clinic->distance, 2, '.', ',')  }} Km
                            </td>
                             @endif
                            <td data-title="Compartir">
                              <div class="btn-group">
                                <!-- <a href="#" class="btn btn-info"><i class="fa fa-address"></i> Consultar ubicación</a> -->
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal" data-address="{{ $clinic->name }} - Direccion: {{ $clinic->province }}, {{ $clinic->canton }}. {{ $clinic->address }}" data-lat="{{ $clinic->lat }}" data-lon="{{ $clinic->lon }}">
                                  <i class="fa fa-address"></i> Compartir
                                </button>
                               
                                <!--<button type="button" class="btn btn-default"><i class="fa fa-align-center"></i></button>-->
                                <a href="tel:26665859" class="btn btn-default"><i class="fa fa-share"></i> Llamar</a>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                        <tr>

                         
                              <td  colspan="3" class="pagination-container">{!!$clinics->appends(['q' => $search['q'], 'province' => $search['province'],'canton' => $search['canton'],'district' => $search['district'],'lat' => $search['lat'],'lon' => $search['lon']])->render()!!}</td>
                         


                          </tr>
                      </table>
                     @endif
                    
                @else
                 
                    <p class="text-muted well well-sm no-shadow text-center" style="margin-top: 10px;">
                      Utiliza al menos uno de los filtros de busqueda para mostrar elementos
                    </p>
                  
                @endif
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>

    </section>
    
     <!-- Modal -->
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">Compartir Ubicación</h4>
                    </div>
                    <div class="modal-body">
                      <div class="share"></div>
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>

@endsection
@section('scripts')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/provinces.min.js"></script>
  <script src="{{ elixir('/js/search.min.js') }}"></script>
  <script>
    /*$(function () {
       
       $('#myModal').on('shown.bs.modal', function (event) {
          
          var button = $(event.relatedTarget)
          var lat = button.attr('data-lat');
          var lon = button.attr('data-lon');
          var address = button.attr('data-address');
              
      
        $(".share").jsSocials({
            shares: ["email", "twitter", "facebook", "googleplus", "whatsapp"],
            url: "http://maps.google.com/?saddr=Current+Location&daddr="+lat +"," + lon,
            text: address,
            showLabel: false,
            showCount: false,
            shareIn: "popup",
           
        });
          
        
     
       
      });

       
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
    });*/
</script>
  
@endsection
