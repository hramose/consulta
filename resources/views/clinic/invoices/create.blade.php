@extends('layouts.app-clinic')
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
           
            <a href="/clinic/invoices" class="btn btn-info">Regresar a facturación</a>
           
         </div>
          <div class="box box-default box-calendar">
          <div class="box-header">
             
           
            </div>
            <div class="box-body ">
             
                    <invoice-general-form  nombre_cliente="" correo_cliente=""  url="/clinic/invoices" url-services="/clinic/invoices" :offices="{{ auth()->user()->offices }}" :current-office="{{ $office->id }}"></invoice-general-form>
									
                
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
