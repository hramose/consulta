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
                  <h3 class="box-title">{{ (type == 2) ? 'Nota de débito' : 'Nota de crédito'}} de la factura: </h3>

                  <div class="box-tools pull-right">
                    
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                     <div class="form-group">
                       <label for="service" class="col-sm-2 control-label">A nombre de:</label>

                        <div class="col-sm-5">
                          <input type="text" v-model="originalInvoice.client_name" name="client_name" class="form-control">
                          <form-error v-if="errors.client_name" :errors="errors" style="float:right;">
                              {{ errors.client_name[0] }}
                          </form-error>
                        </div>
                          <div class="col-sm-5">
                          <input type="text" v-model="originalInvoice.client_email" name="client_email" class="form-control" placeholder="Correo electrónico">
                          <form-error v-if="errors.client_email" :errors="errors" style="float:right;">
                              {{ errors.client_email[0] }}
                          </form-error>
                        </div>
                     </div>
                     <div class="form-group">
                       <label for="service" class="col-sm-2 control-label">Medio de pago:</label>

                        <div class="col-sm-5">
                          <select name="medio_pago" id="medio_pago" v-model="originalInvoice.medio_pago" class="form-control">
                            <option value="01">Efectivo</option>
                            <option value="02">Tarjeta</option>
                          </select>
                          <form-error v-if="errors.medio_pago" :errors="errors" style="float:right;">
                              {{ errors.medio_pago[0] }}
                          </form-error>
                        </div>
                        <div class="col-sm-5">
                          <select name="condicion_venta" id="condicion_venta" v-model="originalInvoice.condicion_venta" class="form-control">
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
                <button type="submit" class="btn btn-default" @click="addToInvoice()">Agregar a Factura</button>
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

              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Documentos de referencia</h3>

                  <div class="box-tools pull-right">
                    
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                     <div class="form-group">
                       <label for="service" class="col-sm-2 control-label">Tipo Documento:</label>

                        <div class="col-sm-3">
                          <select name="tipo_documento" id="tipo_documento" v-model="referencia.tipo_documento" class="form-control">
                            <option :value="item.value" v-for="item in tipos_documento">{{ item.title }}</option>
                          </select>
                          <form-error v-if="errors.tipo_documento" :errors="errors" style="float:right;">
                              {{ errors.tipo_documento[0] }}
                          </form-error>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" v-model="referencia.numero_documento" name="numero_documento" class="form-control" placeholder="Número de documento">
                            <form-error v-if="errors.numero_documento" :errors="errors" style="float:right;">
                                {{ errors.numero_documento[0] }}
                            </form-error>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" v-model="referencia.fecha_emision" name="fecha_emision" class="form-control" placeholder="Fecha emisión">
                            <form-error v-if="errors.fecha_emision" :errors="errors" style="float:right;">
                                {{ errors.fecha_emision[0] }}
                            </form-error>
                        </div>
                     </div>
                     <div class="form-group">
                       <label for="service" class="col-sm-2 control-label">Código referencia:</label>

                        <div class="col-sm-5">
                          <select name="codigo_referencia" id="codigo_referencia" v-model="referencia.codigo_referencia" class="form-control">
                            <option :value="item.value" v-for="item in codigos_referencia">{{ item.title }}</option>
                        
                          </select>
                          <form-error v-if="errors.codigo_referencia" :errors="errors" style="float:right;">
                              {{ errors.codigo_referencia[0] }}
                          </form-error>
                        </div>
                        <div class="col-sm-5">
                          <input type="text" v-model="referencia.razon" name="razon" class="form-control" placeholder="razon">
                            <form-error v-if="errors.razon" :errors="errors" style="float:right;">
                                {{ errors.razon[0] }}
                            </form-error>
                        </div>
                         
                     </div>
                     <div class="form-group">
                         <button type="submit" class="btn btn-success" @click="addReferencia()">Agregar Documento de referencia</button>
                     </div>
                      <div class="table-responsive" v-show="documentosReferencia.length">
                        <table class="table no-margin">
                        <thead>
                        <tr>
                            <th>Número documento</th>
                            <th>Tipo de documento</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in documentosReferencia">
                            
                            <td>{{ item.numero_documento }}</td>
                            <td>{{ getNameFromArrayObject(tipos_documento, item.tipo_documento) }}</td>
                            <td>
                            {{ item.fecha_emision }}
                            </td>
                            <td>
                            <i class="fa fa-trash-o delete" @click="removeReferencia(item)"></i>
                            </td>
                        </tr>
                        
                        </tbody>
                        </table>
                    </div>
                  <!-- /.table-responsive -->
                </div>
            </div>


               
                <div class="form-group">
                  <div class="col-sm-12">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-danger" @click="createNota()">Crear {{ (type == 2) ? 'Nota de débito' : 'Nota de crédito'}} </button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
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
        //props:['invoice', 'office_id','patient_id','office_type','facturar_a','nombre_cliente','correo_cliente','usa_fe'],
         props: {
		    invoice: {
		      type: Object,
		     
		    },
            url:{
                type:String,
                default: '/medic/invoices'
            },
            type:{
                type:String,
                default: '02' // nota de debito
            }
		},
        data () {
	        return {
	 
	        services: [],
            servicesToInvoice: [],
	        loader:false,
	        new_service: "",
            amount: 0,
	        errors: {},
            newService:false,
            updateService:false,
            service:null,
            bill_to:'M',
            originalInvoice:{},
            referencia:{
                tipo_documento: '01',
                numero_documento: '',
                fecha_emision:'',
                codigo_referencia:'',
                razon:''
            },
            documentosReferencia: [],
            tipos_documento:[
                {
                    title: 'Factura Electrónica',
                    value: '01'
                },
                {
                    title: 'Nota de débito',
                    value: '02'
                },
                {
                    title: 'Nota de crédito',
                    value: '03'
                },
                {
                    title: 'Tiquete electrónico',
                    value: '04'
                },
                {
                    title: 'Nota de despacho',
                    value: '05'
                },
                {
                    title: 'Contrato',
                    value: '06'
                },
                 {
                    title: 'Procedimiento',
                    value: '07'
                },
                 {
                    title: 'Comprobante emitido en contingencia',
                    value: '08'
                },
                 {
                    title: 'Otro',
                    value: '99'
                },
            ],
            codigos_referencia:[
                {
                    title: 'Anula Documento de Referencia',
                    value: '01'
                },
                {
                    title: 'Corrige texto documento de referencia',
                    value: '02'
                },
                {
                    title: 'Corrige monto',
                    value: '03'
                },
                {
                    title: 'Referencia a otro documento',
                    value: '04'
                },
                {
                    title: 'Sustituye comprobante provisional por contingencia',
                    value: '05'
                },
                {
                    title: 'Otros',
                    value: '99'
                },
                
            ],


           
	         
	        
	          
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
                ['user_id']: this.originalInvoice.user_id
              }
            this.$http.get(this.url + '/services/list', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               
               this.services = resp.data
               loading(false)
            })
          

        }, 500),
         
          
		  save() {
              
                if(this.loader)
                    return

		              this.loader = true;
		              this.$http.post(this.url + '/services', {name: this.new_service, amount: this.amount, user_id:this.originalInvoice.user_id}).then((response) => {
		                    console.log(response.status);
		                    console.log(response.data);
		                    if(response.status == 200 && response.data)
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
                 var resource = this.$resource(this.url + '/services/'+ this.service.id);

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
              this.$http.delete(this.url + '/services/'+this.service.id).then((response) => {
                    
                    if(response.status == 200 && response.data == 'ok')
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
            addReferencia(){
               if(this.referencia){
                   let errorM = {
                      
                   }
                   if(!this.referencia.tipo_documento)
                   {
                       errorM.tipo_documento = ['Tipo de documento requerido']
                       
                      
                   }
                   if(!this.referencia.numero_documento)
                   {
                        errorM.numero_documento = ['Numero de documento requerido']
                       
                      
                   }
                   if(!this.referencia.fecha_emision)
                   {
                       errorM.fecha_emision = ['Fecha de emisión requerida']
                      
                      
                   }
                   if(!this.referencia.codigo_referencia)
                   {
                        errorM.codigo_referencia = ['Código de referencia requerido']
                       
                      
                   }
                    if(!this.referencia.razon)
                   {
                       errorM.razon = ['Razon requerido']
                       
                      
                   }

                    this.errors = errorM;

                   if(!_.isEmpty(this.errors)){
                       return;
                   }
                   
                   let newDocumentoReferencia = {
                        tipo_documento: this.referencia.tipo_documento,
                        numero_documento: this.referencia.numero_documento,
                        fecha_emision: this.referencia.fecha_emision,
                        codigo_referencia:this.referencia.codigo_referencia,
                        razon: this.referencia.razon
                   };

                  this.documentosReferencia.push(newDocumentoReferencia);
                  this.referencia.codigo_referencia = '';
                  this.referencia.razon = '';

                }
            },
            removeReferencia(item){
           

           
                var index = this.documentosReferencia.indexOf(item)
                 this.documentosReferencia.splice(index, 1);
                  


          },
          getNameFromArrayObject(objArray, id){
              
              let obj = objArray.find(function (obj) { return obj.value === id; });
              
              return obj.title;
          },
	        createNota(){
                let $vm = this;

                if(this.loader)
                   return

                if(!this.documentosReferencia.length){
                    //alert('Necesitar agregar al menos un documento de referencia para poder crear la nota')

                    swal({
                              title: 'Documento de referencia requerido',
                              text: "Necesitar agregar al menos un documento de referencia para poder crear la nota",
                              type: 'error',
                              showCancelButton: false,
                              confirmButtonColor: '#d33',
                              cancelButtonColor: '#3085d6',
                              confirmButtonText: 'Ok',
                              cancelButtonText: 'No'
                            }).then(function () {

                             

                            }, function(dismiss) {
                              
                            });
                    return;
                }

                let urlNota = (this.type == '02') ? 'notadebito' : 'notacredito';
                
                
               
                this.loader = true; 
                this.$http.post(this.url + '/' +this.originalInvoice.id +'/'+ urlNota, { invoice:this.originalInvoice, services: this.servicesToInvoice, type:this.type, referencias:this.documentosReferencia }).then((response) => {
                       
                        if(response.status == 200 && response.data)
                        {
                      
                          bus.$emit('alert', 'Nota de credito o debito creada');
                         
                          this.service = null;
                          this.servicesToInvoice = [];
                          this.errors = [];
                          this.loader = false;

                           swal({
                              title: 'Documento creado',
                              text: "La Nota de crédito o débito fue creada correctamente",
                              type: 'success',
                              //showCancelButton: true,
                              confirmButtonColor: '#d33',
                              cancelButtonColor: '#3085d6',
                              confirmButtonText: 'Ok',
                              cancelButtonText: 'No'
                            }).then(function () {

                              window.location.href = $vm.url;

                            }, function(dismiss) {
                               window.location.href = $vm.url;
                            });
            

                          
                          

                        }
                       this.loader = false;
                  }, (response) => {
                      console.log('error al facturar o enviar nota de credito o debito')
                      console.log(response)
                       this.loader = false;
                       this.errors = response.data.errors;
                       this.invoiceHere = false;
                  });

            }// facturar


       
          
       },
       created(){
            //if(this.office_type == 'Consultorio Independiente')
               this.originalInvoice = this.invoice;
               this.servicesToInvoice = this.invoice.lines
               this.referencia.tipo_documento = this.originalInvoice.tipo_documento
               this.referencia.numero_documento = this.originalInvoice.consecutivo_hacienda
               this.referencia.fecha_emision = this.originalInvoice.created_at
             

       	    console.log('Component ready. InvoiceForm');

          
       }
    }
</script>