<template>
	<div class="form-horizontal">
  
        <form-error v-if="errors.certificate" :errors="errors">
           
            <div class="callout callout-danger">
              <h4>Información importante!</h4>

              <p> {{ errors.certificate[0] }}</p>
            </div>
        </form-error>
        
      <div class="form-invoice-service" v-show="!newService && !updateService">
          <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Factura</h3>

                  <div class="box-tools pull-right">
                    
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                     <div class="form-group">
                       <label for="service" class="col-sm-2 control-label">A nombre de:</label>

                        <div class="col-sm-5">
                          <input type="text" v-model="client_name" name="client_name" class="form-control">
                          <form-error v-if="errors.client_name" :errors="errors" style="float:right;">
                              {{ errors.client_name[0] }}
                          </form-error>
                        </div>
                          <div class="col-sm-5">
                          <input type="text" v-model="client_email" name="client_email" class="form-control" placeholder="Correo electrónico">
                          <form-error v-if="errors.client_email" :errors="errors" style="float:right;">
                              {{ errors.client_email[0] }}
                          </form-error>
                        </div>
                     </div>
                     <div class="form-group">
                       <label for="service" class="col-sm-2 control-label">Medio de pago:</label>

                        <div class="col-sm-5">
                          <select name="medio_pago" id="medio_pago" v-model="medio_pago" class="form-control">
                            <option value="01">Efectivo</option>
                            <option value="02">Tarjeta</option>
                          </select>
                          <form-error v-if="errors.medio_pago" :errors="errors" style="float:right;">
                              {{ errors.medio_pago[0] }}
                          </form-error>
                        </div>
                        <div class="col-sm-5">
                          <select name="condicion_venta" id="condicion_venta" v-model="condicion_venta" class="form-control">
                            <option value="01">Contado</option>
                            <option value="02">Crédito</option>
                          </select>
                          <form-error v-if="errors.condicion_venta" :errors="errors" style="float:right;">
                              {{ errors.condicion_venta[0] }}
                          </form-error>
                        </div>
                         
                     </div>
                </div>
               </div>

          <div class="form-group">
            <label for="service" class="col-sm-2 control-label">Servicio</label>

            <div class="col-sm-5">
               <v-select :debounce="250" :on-search="getServices"  :options="services" placeholder="Selecciona el servicio a facturar..." label="name_price" :on-change="selectService" :value.sync="service"></v-select>
              <form-error v-if="errors.service" :errors="errors" style="float:right;">
                  {{ errors.service[0] }}
              </form-error>
            </div>
            <div class="col-sm-5">
                <button type="submit" class="btn btn-info" @click="addToInvoice()">Agregar a Factura</button>
                <button type="submit" class="btn btn-default" @click="editService()" v-show="service">Modificar Servicio</button>
                <button type="submit" class="btn btn-default" @click="createService()">Crear Servicio</button>
            </div>
          </div>
          <div class="invoice-summary" v-show="servicesToInvoice.length">
               
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Detalle de Factura</h3>

                  <div class="box-tools pull-right">
                    <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th>Cantidad</th>
                        <th>Servicio</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="item in servicesToInvoice">
                        <td>1</td>
                        <td>{{ item.name }}</td>
                        <td>₡{{ money(item.amount) }}</td>
                        <td>
                          ₡{{  money(parseFloat(item.amount) * 1) }}
                        </td>
                        <td>
                          <i class="fa fa-trash-o delete" @click="removeToInvoice(item)"></i>
                        </td>
                      </tr>
                      
                      </tbody>
                    </table>
                  </div>
                  <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <h2 class="pull-right"> Total de Factura: ₡{{  money(getTotalInvoice()) }}</h2> 
                </div>
                 
              </div>



           <div class="form-group">
            <div class="col-sm-12">
              <div class="pull-right">
                  <button type="submit" class="btn btn-info" @click="invoice()">Enviar a secretaria</button>
                  <button type="submit" class="btn btn-success" @click="invoiceHere = true">Facturar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
              </div>
             
            </div>
          </div>
          <div v-show="invoiceHere">
              <div class="box-footer clearfix" >
                 
                  <!-- <div class="row" v-show="!fe && office_type == 'Clínica Privada'">
                    <div class="col-md-6">
                      <div class="">
                        <div class="radio radio-facturar">
                          <label>
                            <input type="radio" name="bill_to"  value="M" v-model="bill_to">
                            Facturar a nombre del médico
                          </label>
                        </div>
                        
                      </div>
                    </div>
                    <div class="col-md-6">
                       <div class="">
                         <div class="radio radio-facturar">
                            <label>
                              <input type="radio" name="bill_to"  value="C"  v-model="bill_to">
                              Facturar a nombre de la clínica
                            </label>
                          </div>
                       </div>
                    </div>
                  </div> -->
                  <div class="row">
                    <div class="col-md-6">
                        <label for="">Pago con</label> ₡<span class="pay_with_label"></span>
                          <div class="input-group pay_with-field">
                          
                            <span class="input-group-addon">₡</span>
                            <input type="text" name="pay_with" class="form-control" placeholder="0" v-model="pay_with" @keyup="calculateChange()">
                          
                          
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="">Vuelto</label> ₡<span class="change_label"></span>
                          <div class="input-group change-field">
                            
                              <span class="input-group-addon">₡</span>
                              <input type="text" name="change" class="form-control" placeholder="0" readonly  v-model="change">
                            
                            
                          </div>
                    </div>
                  </div>
                   
                 
                   
                  
                </div>
                <!-- /.box-footer -->
               
                <div class="form-group">
                  <div class="col-sm-12">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-danger" @click="invoice('here')">Facturar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
                    </div>
                   
                  </div>
                </div>
          </div>
          
              
          </div>
            
         
         
      </div>
      <div class="form-create-service" v-show="newService || updateService">
          <div class="form-group">
            <label for="service" class="col-sm-2 control-label">Nuevo Servicio</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="service" placeholder="Servicio" v-model="new_service">
              <form-error v-if="errors.name" :errors="errors" style="float:right;">
                  {{ errors.name[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="amount" class="col-sm-2 control-label">monto ₡</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="amount" placeholder="Ej: ₡ 1000" v-model="amount">
              <span>Escribe el monto <b>sin comas</b> o <b>puntos</b>. Ejemplo: 10000</span>
              <form-error v-if="errors.amount" :errors="errors" style="float:right;">
                  {{ errors.amount[0] }}
              </form-error>
            </div>
          </div>
           <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success" @click="update()" v-if="updateService">Actualizar</button>
                <button type="submit" class="btn btn-success" @click="save()" v-else>Guardar</button>
                <button type="submit" class="btn btn-danger" @click="remove()" v-show="updateService">Eliminar</button>
                <button type="submit" class="btn btn-info" @click="cancel()">Regresar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
              </div>
            </div>
      </div>
     

	</div>
</template>
<script>
	import FormError from './FormError.vue';
  import vSelect from 'vue-select'
    export default {
        props:['appointment_id', 'office_id','patient_id','office_type','facturar_a','nombre_cliente','correo_cliente','usa_fe'],
        data () {
	        return {
	 
	          services: [],
            servicesToInvoice: [],
	          loader:false,
	          new_service: "",
            amount: 0,
            pay_with:0,
            change:0,
	          errors: [],
            newService:false,
            updateService:false,
            service:null,
            invoiceHere: false,
            bill_to:'M',
            client_name:'',
            client_email:'',
            medio_pago:'01',
            condicion_venta:'01',
            fe:0
           
	         
	        
	          
	        }
	      },
	      components:{
	        FormError,
          vSelect
	       
	      },
        methods: {
          calculateChange(){
              let payWith = parseFloat((this.pay_with) ? this.pay_with : 0);
            let total =  this.getTotalInvoice()
            let change = 0;
            change = ((payWith - total) < 0) ? 0 : payWith - total;
            this.change = change
          },
            money(n, currency) {
                return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
            },
        		cancel() {
		          this.new_service = "";
              this.amount = 0;
		          this.newService = false;
		          this.updateService = false;
		        },
             createService(){
             
              this.newService = !this.newService
              
                this.new_service = "";
                this.amount = 0;
              

            },
            editService(){
             
              
              if(this.service)
              {
               
                 this.updateService = !this.updateService
                this.new_service = this.service.name;
                this.amount = this.service.amount;
              }

            },
            getTotalInvoice(){
              var totalInvoice = 0;
              for (var i = this.servicesToInvoice.length - 1; i >= 0; i--) {

                totalInvoice += this.servicesToInvoice[i].amount * 1;

              }
              return totalInvoice;
            },
            addToInvoice(){
               if(this.service){

                  this.servicesToInvoice.push(this.service);
                  this.service = null;

                }
            },
            removeToInvoice(item){
           

           
                var index = this.servicesToInvoice.indexOf(item)
                 this.servicesToInvoice.splice(index, 1);
                  


          },
            selectService(service) {
           
                  if(service){
                    this.service = service;
                
                  }else{
                    this.service = null;
                  }

           },
            getServices:_.debounce(function(search, loading) {
             

            loading(true)
           
           let queryParam = {
                ['q']: search,
                ['office_id']: this.office_id
              }
            this.$http.get('/medic/invoices/services/list', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               
               this.services = resp.data
               loading(false)
            })
          

        }, 500),
          /*  getServices(search, loading) {
         
            loading(true)
           
           let queryParam = {
                ['q']: search
              }
            this.$http.get('/medic/invoices/services/list', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               
               this.services = resp.data
               loading(false)
            })
            
          },*/
          
		        save() {
              
                if(this.loader)
                    return

		              this.loader = true;
		              this.$http.post('/medic/invoices/services', {name: this.new_service, amount: this.amount, office_id: this.office_id}).then((response) => {
		                    console.log(response.status);
		                    console.log(response.data);
		                    if((response.status == 200 || response.status == 201) && response.data)
		                    {
		                  
		                      bus.$emit('alert', 'Servicio Agregado','success');
		                      this.service = response.data;
                          this.newService = false;
                          this.new_service= "";
                          this.amount = 0;
		                      this.errors = [];
		                    }
		                   this.loader = false;
		              }, (response) => {
		                  console.log('error al agregar servicio')
		                  this.loader = false;
		                   this.errors = response.data.errors;
		              });
		        
		           

		      	},//save service
             update() {
                if(this.loader)
                  return

                this.loader = true;
                 var resource = this.$resource('/medic/invoices/services/'+ this.service.id);

                    resource.update({ name:this.new_service, amount: this.amount}).then((response) => {
                        
                         bus.$emit('alert', 'Servicio Actualizado','success');
                         //bus.$emit('updateList', 'Paciente Actualizado','success');
                         this.service = response.data;
                         this.updateService = false;
                         this.loader = false;
                    }, (response) => {
                        console.log(response.data)
                        this.loader = false;
                        this.loader_message ="Error al guardar cambios";
                        this.errors = response.data.errors;
                    });
              
                  
               

            },//update service
            remove(){
              if(this.loader)
                return

              this.loader = true;
              this.$http.delete('/medic/invoices/services/'+this.service.id).then((response) => {
                    
                    if((response.status == 200 || response.status == 201) && response.data == 'ok')
                    {
                       var index = this.services.indexOf(this.service)
                      this.services.splice(index, 1);
                      this.updateService = false;
                      this.service = null;
                      bus.$emit('alert', 'Servicio Eliminado','success');
                    }
                    this.loader = false;

                }, (response) => {
                    
                     bus.$emit('alert', 'Error al eliminar el Servicio', 'danger');
                    this.loader = false;
                });


            }, //remove
            openInNewTab(url) {
                var a = document.createElement("a");
                a.target = "_blank";
                a.href = url;
                a.click();
            },
	          invoice(here){
                let $vm = this;

                if(this.loader)
                   return

                let status = 0;
                let pay_with = 0;
                let change = 0;
                let sendToAssistant = 1;
                
                if(here) {

                  status = 1;
                  change = this.change
                  pay_with = this.pay_with
                  sendToAssistant = 0;
                }
               
                this.loader = true; 
                this.$http.post('/medic/invoices', { appointment_id:this.appointment_id, office_id:this.office_id, patient_id:this.patient_id, services: this.servicesToInvoice, status: status, pay_with:pay_with, change: change, bill_to: this.bill_to, office_type: this.office_type, client_name: this.client_name, client_email: this.client_email, medio_pago: this.medio_pago, condicion_venta: this.condicion_venta, send_to_assistant: sendToAssistant }).then((response) => {
                       
                        if((response.status == 200 || response.status == 201) && response.data)
                        {
                      
                          bus.$emit('alert', (here) ? 'Servicio facturado' : 'Enviado a asistente','success');
                          bus.$emit('addToInvoiceList', response.data); 
                          this.service = null;
                          this.servicesToInvoice = [];
                          this.errors = [];
                          this.invoiceHere = false;
                          this.loader = false;

                          if(here){

                            swal({
                              title: 'Factura Guardada',
                              text: "¿Deseas imprimir la factura?",
                              type: 'success',
                              showCancelButton: true,
                              confirmButtonColor: '#d33',
                              cancelButtonColor: '#3085d6',
                              confirmButtonText: 'Si',
                              cancelButtonText: 'No'
                            }).then(function () {

                              window.location.href = "/medic/invoices/"+response.data.id+"/print";

                            }, function(dismiss) {
                              
                            });
            



                            //window.location.href = "/medic/invoices/"+response.data.id+"/print";
    
                            //this.openInNewTab("/medic/invoices/"+response.data.id+"/print");
                          }

                        }
                       this.loader = false;
                  }, (response) => {
                      console.log('error al facturar o enviar factura')
                      console.log(response)
                       this.loader = false;
                       this.errors = response.data.errors;
                       this.invoiceHere = false;
                  });

            }// facturar


       
          
       },
       created(){
            //if(this.office_type == 'Consultorio Independiente')
               this.bill_to = this.facturar_a;
               this.client_name = this.nombre_cliente
               this.client_email = this.correo_cliente
               this.fe = this.usa_fe

       	    console.log('Component ready. InvoiceForm');

          
       }
    }
</script>