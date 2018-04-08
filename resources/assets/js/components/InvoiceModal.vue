<template>
    <div class="modal fade" id="modalInvoice" role="dialog" aria-labelledby="modalInvoice">      
  <div class="modal-dialog " role="document">
          <div class="modal-content">
            
              <loading :show="loader"></loading>
           
            <div class="modal-header">
            
            <h4 class="modal-title" id="modalInvoiceLabel">Facturación</h4>
             <!-- <span class="label label-info pull-right">{{ medic }}</span>
             <span class="label label-success pull-left">{{ patient }}</span> -->
             
            </div>

            <div class="modal-body">
               <div class="callout callout-danger hidden">
                <h4>Información importante!</h4>

                <p class="error-certificate"></p>
              </div>
               <div class="form-horizontal">
                    <div class="form-group">
                      <label for="service" class="col-sm-2 control-label">A nombre de:</label>

                      <div class="col-sm-5">
                        <input type="text" name="client_name" class="form-control" v-model="invoice.client_name" :disabled="disableFields">
                        
                      </div>
                        <div class="col-sm-5">
                        <input type="text" name="client_email" class="form-control" v-model="invoice.client_email" :disabled="disableFields" placeholder="Correo electrónico">
                        
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="service" class="col-sm-2 control-label">Medio de pago:</label>

                      <div class="col-sm-5">
                        <select name="medio_pago" id="medio_pago" class="form-control" v-model="invoice.medio_pago" :disabled="disableFields">
                          <option value="01">Efectivo</option>
                          <option value="02">Tarjeta</option>
                        </select>
                      
                      </div>

                         <div class="col-sm-5">
                        <select name="condicion_venta" id="condicion_venta" class="form-control" v-model="invoice.condicion_venta" :disabled="disableFields">
                          <option value="01">Contado</option>
                          <option value="02">Credito</option>
                        </select>
                      
                      </div>
                        
                    </div>
              </div>
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
                        <tr v-for="item in items">
                            <td>{{ item.quantity }} </td>
                            <td>{{ item.name }} </td>
                            <td>{{ money(item.amount) }} </td>
                            <td>{{ money(item.total_line) }} </td>

                        </tr>
                      
                      </tbody>
                      <tfoot>
                        <tr>
                          
                          <td colspan="5"><h2 id="modal-label-total" class="pull-right">Total: {{ money(invoice.total) }}</h2>
                         
                          </td>
                        </tr>
                        <tr>
                          
                          <td colspan="3">
                          <label for="">Pago con</label>
                            <div class="input-group pay_with-field">
                            
                              <span class="input-group-addon">₡</span>
                              <input type="text" name="pay_with" class="form-control"  tabindex="1" v-model="invoice.pay_with" @keypress="isNumber(event)" :disabled="disableFields">
                            
                            
                          </div>
                          </td>
                          <td colspan="3">
                          <label for="">Vuelto</label> 
                            <div class="input-group change-field">
                              
                                <span class="input-group-addon">₡</span>
                                <input type="text" name="change" class="form-control" placeholder="0" v-model="calculateChange" readonly>
                              
                              
                            </div>
                         </td>
                        </tr>
                      </tfoot>
                    </table>
                </div>

                 
            </div>
             <div class="modal-footer" >
             <div class="printers pull-left">
               <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="type_printer" id="rd_ticket" value="ticket" v-model="type_printer">
                      Ticket
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="type_printer"  id="rd_normal" value="normal" checked="checked" v-model="type_printer">
                      Normal
                    </label>
                  </div>
                  
                </div>
             </div>
             
           
             
              
               <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
               <button type="button" class="btn btn-success btn-print" data-invoice data-medic tabindex="2" v-show="invoice.status" @click="print()">Imprimir</button>
              <button type="button" class="btn btn-success btn-facturar" data-invoice data-medic tabindex="2" v-show="!invoice.status" @click="facturar()">Facturar</button>
             
            </div>
          </div>
        </div>
      </div>
</template>
<script>
export default {
   props:['url'],
   data (){
       return {
           items:[],
           invoice:{},
           endpoint: this.url ? this.url : '/medic/invoices',
           loader:false,
           type_printer:'normal',
           invoiceId: false
       }
   },
    computed: {
      calculateChange() {
        let change = this.invoice.pay_with - this.invoice.total;

        return  (change < 0) ? 0 : change;
      },
      disableFields() {
        return  this.invoice.status ? 'disabled' : false;
      }
    },

   methods:{
      isNumber: function(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
          evt.preventDefault();;
        } else {
          return true;
        }
      },

      money(n, currency) {
                return (n) ? n.toLocaleString() : 0;
            },
       fetch(){
           this.loader = true;
            this.$http.get(this.endpoint +'/'+ this.invoiceId +'/details').then((response) => {
    
                 this.invoice = response.data;
                 this.items = response.data.lines;
                   
                  this.loader = false;

              }, (response) => {
                 
                  bus.$emit('alert', 'Error al cargar Factura', 'danger');
                  this.loader = false;
              });
       },
       facturar(){
       
         
         if(this.loader) return;

          this.loader = true;
          
          this.invoice.change = this.calculateChange

         var resource = this.$resource(this.endpoint +'/'+ this.invoiceId);

                    resource.update(this.invoice).then((response) => {
                         this.loader = false;
                         bus.$emit('alert', 'Factura procesada!!!','success');
                          swal({
                            title: 'Factura procesada!!!',
                            text: "¿Deseas imprimir la factura?",
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            cancelButtonText: 'No',
                            confirmButtonText: 'Si'
                          }).then( (resp) => {

                            if (this.type_printer == 'ticket')
                              window.location.href = this.endpoint +'/'+ this.invoiceId + '/ticket';
                            else
                              window.location.href = this.endpoint +'/'+ this.invoiceId + '/print';

                          }, (dismiss) => { 

                            window.location.href = this.endpoint

                          });

                    }, (response) => {
                        
                        this.loader = false;
                        this.loader_message ="Error al guardar cambios";
                        this.errors = response.data.errors;
                    });

       },

       print(){

          if (this.type_printer == 'ticket')
            window.location.href = this.endpoint +'/'+ this.invoiceId + '/ticket';
          else
            window.location.href = this.endpoint +'/'+ this.invoiceId + '/print';

       }
   },
   created(){
       
        window.bus.$on('showInvoiceModal', (data) => {
               this.invoiceId = data
               this.invoice = {}
               this.items = []
               this.fetch()
        });
   }
}
</script>
