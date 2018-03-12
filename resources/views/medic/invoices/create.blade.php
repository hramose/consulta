@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">

@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
     
   
    
    <section class="content">
       
        <div class="row">
        
        <div class="col-md-12">
         
       
          <h2>Crear Factura</h2>
          <div>
           
            <a href="/medic/invoices" class="btn btn-info">Regresar a facturación</a>
           
         </div>
          <div class="box box-default box-calendar">
          <div class="box-header">
             
           
            </div>
            <div class="box-body ">
                @if( auth()->user()->fe && auth()->user()->configFactura )
                    <invoice-general-form  nombre_cliente="" correo_cliente="" :usa_fe="{{ auth()->user()->fe }}"></invoice-general-form>
									
                @else
                    <div class="callout callout-danger">
                        <h4>Información importante!</h4>

                        <p>No tienes configurado los parametros para la factura electronica. Por favor configuralos para poder continuar. <a href="/medic/account/edit?tab=fe" title="Ir a configurar Factura Electronica"><b>Configurar Factura Electrónica</b></a></p>
                    </div>
               @endif
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          
        </div>
       
      </div>
      <!-- /.row -->

    </section>

   
@endsection
@section('scripts')
<script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
@endsection
