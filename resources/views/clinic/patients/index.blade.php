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
              
              <form action="/clinic/patients" method="GET">
                <div class="form-group">
                    <div class="col-sm-2">
                      <a href="{{ url('/clinic/patients/create') }}" class="btn btn-success">Crear Nuevo paciente</a>
                    </div>

                    <div class="col-sm-3">
                      <div class="input-group ">
                    
                          
                         <input type="text" name="q" class="form-control" placeholder="Buscar..." value="{{ isset($search) ? $search['q'] : '' }}">
                          <div class="input-group-btn">

                            <button type="submit" class="btn btn-primary">Buscar</button>
                          </div>
                        
                        
                      </div>
                      
                    </div>
                      <div class="col-sm-2">
                        <select name="province" id="province" class="form-control">
                          <option value="">-- Selecciona Provincia --</option>
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
                </form>
                  

              </div>  
              <!-- /.box-header -->

             
                   
              <div class="box-body table-responsive no-padding" id="no-more-tables">
                
               
               <form action="/patients/marketing" method="POST" id="send-marketing" enctype="multipart/form-data">
                    @csrf
                     
                    
                  <table class="table table-hover">
                  <thead>
                    <tr>
                      <th><input type="checkbox" name="select_all_patients" id="select_all_patients"/> 
                      <input type="hidden" name="select_action" id="select_action"/> </th>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Teléfono</th>
                      <th>Email</th>
                      <th>Dirección</th>
                      <th>Acciones</th>
                     
                    </tr>
                  </thead>
                  @foreach($patients as $patient)
                    <tr>
                      <td data-title="Marketing">
                          <input type="checkbox" name="patients[]" value="{{ $patient->id }}" class="chk-item">
                       </td>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                      <b>Marketing:</b> <small>Selecciona los paciente que deseas enviarles información</small>
                    </div>
                    <div class="panel-body">
                        
                        <div class="marketing-info row">
                            <div class="form-group">
                           
                              <div class="col-sm-3">
                                
                                
                                      
                                  <input type="text" name="title" class="form-control" placeholder="Titulo: Nuevo anuncio..." value="">
                                      
                                    
                                    
                                  
                              </div>
                              <div class="col-sm-8">
                                
                                
                                      
                                  <input type="text" name="body" class="form-control" placeholder="Descripción: Te ha llegado una nueva notificacion de informacion de interes, revisala en el panel de notificaciones!!" value="">
                                      
                                    
                                    
                                  
                              </div>
                            </div>
                        </div>
                        <div></div>
                        <div class="marketing row">
                           <div class="col-sm-12">
                              <div class="marketing-flex">
                                  <div class="marketing-file">
                                    
                                        <input type="file" name="file" id="file" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" />
                                        <label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Escoger archivo&hellip;</span></label>
                                      
                                    
                                  
                                  </div>
                                  <div class="button-file-send">
                                          
                                        <button type="submit" class="btn-multiple btn btn-danger btn-sm btn-flat btn-block" data-action="send-marketing" title="Enviar" form="send-marketing" formaction="/patients/marketing"><i class="fa fa-send"></i> Enviar anuncio </button>
                                    
                                  
                                  </div>
                              </div>
                              
                            </div>
                        </div>
                        


                    </div>
                    
                </div>
                
                

               </form>
               
             
            
                
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
  <script>

      var chkItem = $('.chk-item'),
          chkSelectAll = $('#select_all_patients');

      chkSelectAll.on('click', function (e) {
         var c = this.checked;
         $(':checkbox').prop('checked',c);
      });

    $('.btn-multiple').on('click',function(e) {
        
        if($('input[name="file"]').val()){
        
        var action = $(this).data('action');

        chkSelectAll.val(action);
        $('#select-action').val(action);

        (verificaChkActivo(chkItem)) ? $('#send-marketing').submit() : alert('Seleccione al menos un paciente de la lista');
        
        }else {
          alert('Seleccione un archivo');

        }

        e.preventDefault();

    });

    function verificaChkActivo(chks) {
        var state = false;

        chks.each(function(){

            if(this.checked)
            {

                state = true;


            }

        });

        return state;
    }

    $('select[name="province"]').on('change', function (e) {
   

    $(this).parents('form').submit();

});

      

  </script>
@endsection
