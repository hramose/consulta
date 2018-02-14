<div class="modal fade" id="modalRespHacienda" role="dialog" aria-labelledby="modalRespHacienda">
        <div class="modal-dialog " role="document">
          <div class="modal-content">
            <div class="modal-header">
            
                <h4 class="modal-title" id="modalRespHaciendaLabel">Estatus de Factura Hacienda</h4>
            
            </div>

            <div class="modal-body">
                @include('layouts/partials/loading')
              <ul>
                  <li><b>Clave: </b> <span id="resp-clave"><span></li>
                  <li><b>Emisor: </b><span id="resp-emisor"><span></li>
                  <li><b>Receptor: </b><span id="resp-receptor"><span></li>
                  <li><b>Mensaje: </b><span id="resp-mensaje"><span></li>
                  <li><b>Detalle Mensaje: </b><span id="resp-detalle"><span></li>
              </ul>
             
                 
            </div>
             <div class="modal-footer" >
             
             
              
               <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              
             
            </div>
          </div>
        </div>
      </div>