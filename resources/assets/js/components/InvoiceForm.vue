<template>
	<div class="form-horizontal">
  
      
      <div class="form-invoice-service" v-show="!newService">
          <div class="form-group">
            <label for="service" class="col-sm-2 control-label">Servicio</label>

            <div class="col-sm-5">
               <v-select :debounce="250" :on-search="getServices"  :options="services" placeholder="Selecciona el consultorio para la cita..." label="name" :on-change="selectService" :value.sync="service"></v-select>
              <form-error v-if="errors.service" :errors="errors" style="float:right;">
                  {{ errors.service[0] }}
              </form-error>
            </div>
            <div class="col-sm-5">
                <button type="submit" class="btn btn-default" @click="addToInvoice()">Agregar a Factura</button>
                <button type="submit" class="btn btn-default" @click="newService = !newService">Crear Servicio</button>
            </div>
          </div>
          <div class="invoice-summary" v-show="servicesToInvoice.length">
              
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Resumen de Factura</h3>

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
                        <td>₡{{ money(item.amount) }}</span></td>
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
                    Total de Factura: ₡{{  money(getTotalInvoice()) }}
                </div>
                <!-- /.box-footer -->
              </div>



           <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-success" @click="invoice()">Facturar</button>
            </div>
          </div>
              
          </div>
            
         
         
      </div>
      <div class="form-create-service" v-show="newService">
          <div class="form-group">
            <label for="service" class="col-sm-2 control-label">Nuevo Servicio</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="service" placeholder="Servicio" v-model="new_service">
              <form-error v-if="errors.new_service" :errors="errors" style="float:right;">
                  {{ errors.new_service[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="amount" class="col-sm-2 control-label">monto</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="amount" placeholder="amount" v-model="amount">
              <form-error v-if="errors.amount" :errors="errors" style="float:right;">
                  {{ errors.amount[0] }}
              </form-error>
            </div>
          </div>
           <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success" @click="save()">Guardar</button>
                <button type="submit" class="btn btn-danger" @click="cancel()">Cancelar</button>
              </div>
            </div>
      </div>
     

	</div>
</template>
<script>
	import FormError from './FormError.vue';
  import vSelect from 'vue-select'
    export default {
      
        data () {
	        return {
	 
	          services: [],
            servicesToInvoice: [],
	          loader:false,
	          new_service: "",
            amount: 0,
	          errors: [],
            newService:false,
            service:null
           
	         
	        
	          
	        }
	      },
	      components:{
	        FormError,
          vSelect
	       
	      },
        methods: {
            money(n, currency) {
                return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
            },
        		cancel() {
		          this.new_service = "";
		          this.newService = false;
		        
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
                
                  }

           },
            getServices(search, loading) {
         
            loading(true)
           
           let queryParam = {
                ['q']: search
              }
            this.$http.get('/medic/invoices/services/list', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               
               this.services = resp.data
               loading(false)
            })
            
          },
          
		        save() {

		          
		              this.$http.post('/medic/invoices/services', {name: this.new_service, amount: this.amount}).then((response) => {
		                    console.log(response.status);
		                    console.log(response.data);
		                    if(response.status == 200 && response.data)
		                    {
		                  
		                      bus.$emit('alert', 'Servicio Agregado','success');
		                      this.service = response.data;
                          this.newService = false;
		                      this.errors = [];
		                    }
		                   this.loader = false;
		              }, (response) => {
		                  console.log('error al agregar servicio')
		                  this.loader = false;
		                   this.errors = response.data;
		              });
		        
		           

		      	},//save service
	          invoice(){

                this.$http.post('/medic/invoices', this.servicesToInvoice).then((response) => {
                        console.log(response.status);
                        console.log(response.data);
                        if(response.status == 200 && response.data)
                        {
                      
                          bus.$emit('alert', 'Servicio facturado','success');
                          this.service = null;
                          this.servicesToInvoice = [];
                          this.errors = [];
                        }
                       this.loader = false;
                  }, (response) => {
                      console.log('error al facturar servicio')
                      this.loader = false;
                       this.errors = response.data;
                  });

            }// facturar


       
          
       },
       created(){

       	    console.log('Component ready. InvoiceForm');

          
       }
    }
</script>