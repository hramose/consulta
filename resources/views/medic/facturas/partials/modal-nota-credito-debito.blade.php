<div class="modal fade" id="modalNotaCreditoDebito" role="dialog" aria-labelledby="modalNotaCreditoDebito">
        <div class="modal-dialog " role="document">
          <div class="modal-content">
             @include('layouts/partials/loading')  
            <div class="modal-header">
            
                <!-- <h4 class="modal-title" id="modalNotaCreditoDebitoLabel">Creacion de nota de credito</h4> -->
            
            </div>

            <div class="modal-body">
              <nota-credito-debito-form :invoice="{{ $factura->load('lines') }}" :type="{{ $typeDocument }}"></nota-credito-debito-form>
                 
            </div>
             <div class="modal-footer" >
             
             
              
               <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              
             
            </div>
          </div>
        </div>
      </div>