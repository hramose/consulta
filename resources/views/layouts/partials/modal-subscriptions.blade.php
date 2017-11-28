 <div class="modal fade" id="modalSubscription" role="dialog" aria-labelledby="modalSubscription">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
          
         
            <h4 class="modal-title">Parece que no tienes una <b>subscripción</b> todavia! Selecciona una para continuar con el proceso</h4>
          </div>
          <div class="modal-body" >
              
             <div class="text-center">
                <table-subscriptions token="{{ csrf_token() }}"></table-subscriptions>
            </div>
              
                
          </div>
            <div class="modal-footer" >
            
            
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            
          </div>
        </div>
      </div>
    </div>