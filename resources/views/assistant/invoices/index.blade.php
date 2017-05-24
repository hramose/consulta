@extends('layouts.app-assistant')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="/js/plugins/fullcalendar/fullcalendar.print.css" media="print">
  <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
  <link rel="stylesheet" href="/js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css"> 

@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
    
     @if($medic)
       @include('layouts/partials/header-pages',['page'=>'Facturas del medico '. $medic->name ])
    @else
       @include('layouts/partials/header-pages',['page'=>'Facturación'])
    @endif
     
    
    <section class="content">
       
        <div class="row">
        <div class="col-md-4">
          
          
         
       
          <div class="box box-solid box-medics">
            <div class="box-header with-border">
              <h4 class="box-title">Médicos </h4>
              <div><small>(Haz click en un medico para ver sus facturas)</small></div>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-medics">
                <ul class="medic-list medic-list-in-box">
                  @foreach($medics as $doctor)
                    <li class="item {{ (isset($medic) && $doctor->id == $medic->id) ? 'medic-list-selected': '' }}">
                      <div class="medic-img">
                      <!--/img/default-50x50.gif-->
                        <img src="{{ Storage::url('avatars/'.$doctor->id.'/avatar.jpg') }}" alt="Medic Image" width="50" height="50">
                      </div>
                      <div class="medic-info">
                        <a href="/assistant/medics/{{$doctor->id }}/invoices" class="medic-title">{{ $doctor->name }}
                          </a>
                         
                           
                            <a href="/assistant/medics/{{$doctor->id }}/invoices" class="label  label-info pull-right">Ver Facturas</a>
                           
                         
                            <span class="medic-description">
                              E: {{ $doctor->email }}, T: {{ $doctor->phone }}
                            </span>
                      </div>
                    </li>
                   
                  @endforeach
                  
                </ul>
                @if ($medics)
                        <div  class="pagination-container">{!!$medics->render()!!}</div>
                    @endif
               
              </div>
            </div>
            <!-- /.box-body -->
          </div>
       
          
          
        </div>
        <!-- /.col -->
        <div class="col-md-8">
         
          @if($invoices)
          <div class="box box-default box-calendar">
            <div class="box-header">
               <strong> Ultimas Facturas</strong> 
            </div>
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->

                 <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>Médico</th>
                        <th>Clínica</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($invoices as $invoice)
                          <tr>
                            <td>{{ $invoice->id }}</td>
                             <td>{{ $invoice->medic->name }}</td>
                             <td>{{ $invoice->clinic->name }}</td>
                            <td>
                             {{ $invoice->created_at }}
                            </td>
                           
                            <td>{{ money($invoice->total) }}</span></td>
                            <td>
                                @if($invoice->status)
                                  <span class="label label-success">Facturada</span>
                                @endif
                            </td>
                            <td>
                            
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalInvoice" data-id="{{ $invoice->id }}">
                                  <i class="fa fa-eye"></i> Detalle
                                </button>
                            </td>
                          </tr>
                      @endforeach
                      
                      </tbody>
                     
                    </table>
                  </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          @else
            <div class="box box-default box-calendar">
            <div class="box-body ">
              <!-- THE CALENDAR -->
               <div class="callout callout-info">
                    <h4>Información importante!</h4>

                    <p>Selecciona un Médico para ver sus facturas</p>
                </div>
                
                

        
            </div>
            <!-- /.box-body -->
          </div>
          @endif
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>

     <div class="modal fade" id="modalInvoice" role="dialog" aria-labelledby="modalInvoice">
        <div class="modal-dialog " role="document">
          <div class="modal-content">
            <div class="modal-header">
            
            <h4 class="modal-title" id="modalInvoiceLabel">Facturación</h4>
            <span class="label label-info pull-right">Médico: <span id="modal-label-medic"></span></span>
            <span class="label label-info pull-right">Paciente: <span id="modal-label-patient"></span></span>
            <input type="hidden" name="client_name" />
            </div>

            <div class="modal-body">
                
              
               <div class="table-responsive">
                    <table class="table no-margin" id="table-details">
                      <thead>
                      <tr>
                        <th>Cantidad</th>
                        <th>Servicio</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                        
                      </tr>
                      </thead>
                      <tbody>
                      
                      
                      </tbody>
                      <tfoot>
                        <tr>
                          
                          <td colspan="5"><h2 id="modal-label-total" class="pull-right">Total: 0</h2></td>
                        </tr>
                      </tfoot>
                    </table>
                </div>

                 
            </div>
             <div class="modal-footer" >
             
             
               <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="button" class="btn btn-primary btn-facturar" data-invoice data-medic>Facturar</button>
             
            </div>
          </div>
        </div>
      </div>
  


@endsection
@section('scripts')
<script src="/js/bootstrap.min.js"></script>
<script src="{{ elixir('/js/assistant.invoices.min.js') }}"></script>

@endsection
