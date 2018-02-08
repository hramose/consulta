<template>
	
    <div>
        <div>
            <h3 class="label label-danger">{{ error }}</h3>
        </div>
         
         <div class="form-group">
            <button @click="testConexion()" class="btn btn-success">Probar Conexion Hacienda</button>
            <button @click="clear()" class="btn btn-default">Limpiar mensajes</button>
        </div>
       <div class="form-group">
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-danger" @click="generaFacturaPrueba()">Generar y enviar</button>
                </div>
                <input type="number" v-model="consecutivo" class="form-control" placeholder="Consecutivo a crear" min="1" />
            </div>
       </div>
        
       
        <ul v-show="fe.clave">
            <li><b>Clave:</b> {{ fe.clave }}</li>
            <li><b>Fecha:</b> {{ fe.fecha }}</li>
            <li><b>Estado:</b> {{ fe["ind-estado"] }}</li>
            
            <li><button @click="verEstadoFactura(fe.clave)" class="btn btn-default">Ver Estado</button></li>
        </ul>

        <div class="form-group">
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-info" @click="verEstadoFactura(claveFactura)">Ver Estado de factura</button>
                </div>
                <input type="text" v-model="claveFactura" class="form-control" placeholder="Clave (50 digitos) Ej. 50601011600310112345600100010100000000011999999999"/>
            </div>
       </div>
       <div class="form-group">
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-info" @click="buscarComprobante(claveComprobante)">Buscar Comprobante (Aceptado)</button>
                </div>
                <input type="text" v-model="claveComprobante" class="form-control" placeholder="Clave (50 digitos) Ej. 50601011600310112345600100010100000000011999999999"/>
            </div>
       </div>

         <ul v-show="respRecepcion.Clave">
            <li><b>Clave:</b>    {{ respRecepcion.Clave }}</li>
            <li><b>Mensaje:</b> {{ respRecepcion.Mensaje }} - {{ getStatusMensaje(respRecepcion.Mensaje) }}</li>
            <li><b>DetalleMensaje:</b> {{ respRecepcion.DetalleMensaje }}</li>
         
        </ul>
        <ul v-show="respComprobante.clave">
            <li><b>Clave:</b>    {{ respComprobante.clave }}</li>
            <li><b>Fecha:</b> {{ respComprobante.fecha }} </li>
            <li><b>Emisor:</b> {{ (respComprobante.emisor) ? respComprobante.emisor.numeroIdentificacion +' '+respComprobante.emisor.nombre : '' }}</li>
            <li><b>Receptor:</b> {{ (respComprobante.receptor) ? respComprobante.receptor.numeroIdentificacion + ' '+respComprobante.receptor.nombre : ''}} </li>
             <li><b>Notas Credito:</b> {{ respComprobante.notasCredito }} </li>
             <li><b>Notas Debito:</b> {{ respComprobante.notasDebito }} </li>
         
        </ul>
        <h3>{{ result }}</h3>
        <div style="text-align:center;">
            <img src="/img/loading.gif" alt="Cargando..." v-show="loader">
        </div>
    </div>

    
            
      

  
</template>

<script>
    export default {
      props:['userId'],
      data () {
        return {
          error : "",
          result: "",
          consecutivo : 1,
          loader:false,
          claveFactura: '',
          claveComprobante: '',
          fe:{ },
          respRecepcion:{
              Clave:'',
              Mensaje:'',
              DetalleMensaje: ''
          },
          respComprobante:{},
          mensajes:['Aceptado','Aceptacion Parcial','Rechazado']


        }
          
      },
      
      methods: {
          
          clear(){
            this.respRecepcion = {
                    Clave:'',
                    Mensaje:'',
                    DetalleMensaje: ''
                }
            this.respComprobante = {};
            this.fe = {};
            this.error = "";
            this.result = "";
          },
          getStatusMensaje(codigo){
              return this.mensajes[codigo-1];
          },
          testConexion() {

            this.loader = true;
            this.clear();
            this.$http.post('/users/'+ this.userId +'/fe/conexion').then((response) => {

                  
                  console.log(response);
                  

                  if(response.status == 200)
                  {
                    if(response.data.access_token)
                        this.result = 'Conexion Exitosa'
                    else
                        this.error = 'Ha ocurrido un error en la Conexion con hacienda 1';
                    
                  }
                  this.loader = false;
                 
              }, (response) => {
                 
                   console.error(response);
                   this.error = 'Ha ocurrido un error en la Conexion con hacienda 2';
                  this.loader = false;
              });

            

          },
          generaFacturaPrueba() {
            
             if(!this.consecutivo) return;

            this.loader = true;
            this.clear();
            this.$http.post('/users/'+ this.userId +'/fe/generate/'+ this.consecutivo).then((response) => {

                  
                  console.log(response);
                  

                  if(response.status == 200)
                  {
                     //this.fe = response.data;
                     this.fe = response.data;
                  }
                  this.loader = false;
                  //ss

              }, (response) => {
                 
                   console.error(response);
                   this.error = 'Ha ocurrido un error en el envio de la factura';
                  this.loader = false;
              });

            

          },
          verEstadoFactura(clave) {

             if(!clave) return;

             this.loader = true;
             this.respRecepcion = {
                    Clave:'',
                    Mensaje:'',
                    DetalleMensaje: ''
                }
            this.$http.get('/users/'+ this.userId +'/fe/recepcion/'+ clave).then((response) => {

                  
                  console.log(response);
                  

                  if(response.status == 200)
                  {
                     this.respRecepcion = response.data;
                    
                  }
                  this.loader = false;

              }, (response) => {
                 
                   console.error(response);
                   this.error = 'Ha ocurrido un error en la busqueda de la factura';
                  this.loader = false;
              });

            

          },

          buscarComprobante(clave) {

             if(!clave) return;

             this.loader = true;
             this.respComprobante = {}
            this.$http.get('/users/'+ this.userId +'/fe/comprobantes/'+ clave).then((response) => {

                  
                  console.log(response);
                  

                  if(response.status == 200)
                  {
                      this.respComprobante = response.data;
                    
                  }
                  this.loader = false;

              }, (response) => {
                 
                   console.error(response);
                   this.error = 'Ha ocurrido un error en la busqueda de la factura';
                  this.loader = false;
              });

            

          }
        
     
      },
      created () {
           console.log('Component ready. test conexion hacienda.')
          
          
           
      }
      
    }
</script>