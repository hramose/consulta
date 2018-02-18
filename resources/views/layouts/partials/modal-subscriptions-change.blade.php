 <div class="modal fade" id="modalSubscriptionChange" role="dialog" aria-labelledby="modalSubscriptionChange" style="z-index: 99999;">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
          
         
            <h4 class="modal-title">Parece que no tienes una <b>subscripción</b> todavia o esta se encuentra vencidad! Selecciona una para continuar con el proceso de cambio o compra</h4>
          </div>
          <div class="modal-body" >
              
             <div class="text-center">
                @if(isset($change))
                  <table-subscriptions token="{{ csrf_token() }}" change="{{ $change }}" current-plan="{{ auth()->user()->subscription->plan_id }}"></table-subscriptions>
                @else 
                  <table-subscriptions token="{{ csrf_token() }}"></table-subscriptions>
                @endif
            </div>
              
                
          </div>
            <div class="modal-footer" >
            
            
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            
          </div>
        </div>
      </div>
    </div>