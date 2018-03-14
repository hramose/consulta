<template>
    <div>
        <span :class="'label label-'+ message.status">{{ message.body }}</span>
        <button class="btn btn-sm btn-success" @click="send()" v-show="show">Reenviar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
    </div>
	
</template>
<script>

 export default {
        
         props: {
             invoiceId: {
                type:Number,
                default: 0
             },
             url:{
                type:String,
                default: '/medic/invoices'
            },
            read:{
                type:Boolean,
                default: false
            },
          
		    
		},
        data () {
	        return {
	 
	          invoice:{},
              loader:false,
              message:{
                  body:'No enviado por error de conexion',
                  status:'danger'
              },
              show:true
    
             
	          
	        }
	      },
	      
        methods: {
           
	        send(){
                
                if(!this.invoiceId) return;

                this.loader = true;
                this.show = false;

                var resource = this.$resource(this.url +'/'+ this.invoiceId );

                resource.update().then((response) => {
                    
                     
                         this.loader = false;
                        if((response.status == 200 || response.status == 201) && response.data)
                        {
                            if(response.data.sent_to_hacienda)
                            {
                                bus.$emit('alert', 'Factura enviada correctamente. Esperando respuesta por parte de hacienda','success');
                                this.message.body = 'Esperando respuesta de hacienda';
                                this.message.status = 'warning';
                                this.show = false;
                            }else{
                                bus.$emit('alert', 'Error al enviar Factura. No hubo conexion con hacienda','danger');
                                this.show = true;
                            }
                        }
                       console.log(response.data)
                     
                }, (response) => {
                    console.log(response.data)
                    bus.$emit('alert', 'Error al enviar Factura','danger');
                    this.loader = false;
                    this.show = true;
                   
                });

               
            }
           

       	
        },//methods
        created() {
          
	     
	      
	    }
    }
</script>