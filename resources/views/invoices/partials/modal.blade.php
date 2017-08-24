<div class="modal fade" id="modalInvoice" role="dialog" aria-labelledby="modalInvoice">
        <div class="modal-dialog " role="document">
          <div class="modal-content">
            <div class="modal-header">
            
            <h4 class="modal-title" id="modalInvoiceLabel">Facturación</h4>
            <span class="label label-info pull-right">Médico: <span id="modal-label-medic"></span></span>
             <span class="label label-success pull-left">Paciente: <span id="modal-label-patient"></span></span>
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
             <div class="printers pull-left">
               <!-- <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="type_printer" id="rd_ticket" value="ticket">
                      Ticket
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="type_printer"  id="rd_normal" value="normal" checked="checked">
                      Normal
                    </label>
                  </div>
                  
                </div> -->
             </div>
             
           
             
              
               <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <!-- <button type="button" class="btn btn-primary btn-facturar" data-invoice data-medic>Facturar</button> -->
             
            </div>
          </div>
        </div>
      </div>