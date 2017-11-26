 <div class="modal fade" id="modalPendingPayments" role="dialog" aria-labelledby="modalPendingPayments">
      <div class="modal-dialog " role="document">
        <div class="modal-content">
          <div class="modal-header">
          
         
           <h4 class="modal-title">Tienes pagos pendientes!. Haz el pago para poder continuar</h4>
          </div>
          <div class="modal-body" >
              
             <div class="text-center">
                <table-pending-payments :monthly-charges="{{ auth()->user()->monthlyCharge() }}" token="{{ csrf_token() }}"></table-pending-payments>
            </div>
              
                
          </div>
            <div class="modal-footer" >
            
            
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            
          </div>
        </div>
      </div>
    </div>