@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">

@endsection
@section('content')
    <div id="infoBox" class="alert"></div> 
     
   
    
    <section class="content">
       
        <div class="row">
        
        <div class="col-md-12">
         
       
          <h2>Generacion de Notas de débito y crédito</h2>
          <div>
           
            <a href="/medic/invoices" class="btn btn-info">Regresar a facturación</a>
           
         </div>
          <div class="box box-default box-calendar">
          <div class="box-header">
             
           
            </div>
            <div class="box-body ">
             
                <nota-credito-debito-form :invoice="{{ $invoice->load('lines') }}" type="{{ $typeDocument }}"></nota-credito-debito-form>
               
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
