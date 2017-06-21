<template>
  <div>
    <div class="callout callout-danger" v-show="!offices.length">
        <h4>No tienes registrado consultorios Independientes!</h4>

        <p>Para agregar asistentes necesitas al menos un consultorio independiente. Si estas trabajando con clínicas privadas estos asistentes no son necesarios.</p>
      </div>
  	<div class="form-horizontal" v-show="offices.length">
        
        <div class="form-group">
          <label for="asistente_name" class="col-sm-2 control-label">Nombre</label>

          <div class="col-sm-10">
            <input type="text" class="form-control" name="name" placeholder="Nombre del asistente" v-model="asistente.name" >
            <form-error v-if="errors.name" :errors="errors" style="float:right;">
                {{ errors.name[0] }}
            </form-error>
            </div>
        </div>
        
        <div class="form-group">
          <label for="asistente_email" class="col-sm-2 control-label">Email</label>

          <div class="col-sm-10">
            <input type="email" class="form-control" name="email" placeholder="Email" v-model="asistente.email" :disabled="asistente.id == '' ? false : true">
            <form-error v-if="errors.email" :errors="errors" style="float:right;">
                {{ errors.email[0] }}
            </form-error>
          </div>
        </div>
        <div class="form-group">
          <label for="asistente_password" class="col-sm-2 control-label">Contraseña</label>

          <div class="col-sm-10">
            <input type="password" class="form-control" name="password" placeholder="Contraseña"  v-model="asistente.password">
            <form-error v-if="errors.password" :errors="errors" style="float:right;">
                {{ errors.password[0] }}
            </form-error>
          </div>
        </div>
        <div class="form-group">
          <label for="asistente_office_id" class="col-sm-2 control-label">Consultorio</label>

          <div class="col-sm-10">
            <select class="form-control " style="width: 100%;" name="office_id" v-model="asistente.office_id">
              <option></option>
              <option v-for="item in offices" v-bind:value="item.id"> {{ item.name }}</option>
              
            </select>
            <form-error v-if="errors.office_id" :errors="errors" style="float:right;">
                {{ errors.office_id[0] }}
            </form-error>
          </div>
        </div>
       
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success" @click="save()">Guardar</button>
            <button type="submit" class="btn btn-danger" @click="clear()">Cancelar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
          </div>
        </div>
  	</div>
  </div>
</template>
<script>
	import FormError from './FormError.vue';

    export default {
        props: {
		    clinics: {
		      type: Array
		     
		    },
        url:{
          type:String,
          default: '/medic/account/assistant'
        }
		},
        data () {
	        return {
	 
	          offices: [],
	          
	          loader:false,
	          //fromModal: false,
	          asistente: {
                id:'',
                name:'',
                email:'',
                password:'',
                office_id:''
            },
	          errors: []
	         
	        
	          
	        }
	      },
	      components:{
	        FormError
	       
	      },
        methods: {

        		clear() {
		          this.asistente = {
                  id:'',
                  name:'',
                  email:'',
                  password:'',
                  office_id:''
              };
		          
		        },
        
		        edit(asistente){

		        	this.asistente = asistente;
              this.asistente.office_id = asistente.clinics_assistants[0].id;
		        },
		         
		        save() {
              this.loader = true;
		          //var resource = this.$resource('/medic/account/offices');
		           if(this.asistente.id)
		           {
		             var resource = this.$resource(this.url +'/'+ this.asistente.id);

		                resource.update(this.asistente).then((response) => {
		                    
		                     bus.$emit('alert', 'Asistente Actualizado','success');
		                     bus.$emit('updateAssistantList');
		                     this.loader = false;
		                     this.errors = [];
		                     this.clear();
		                }, (response) => {
		                    console.log(response.data)
		                    this.loader = false;
		                    this.loader_message ="Error al guardar cambios";
		                    this.errors = response.data;
		                });

		           }else{
		              this.$http.post(this.url, this.asistente).then((response) => {
		                    console.log(response.status);
		                    console.log(response.data);
		                    if(response.status == 200 && response.data)
		                    {
		                      
		                      bus.$emit('addToAssistantList', response.data); //this.pacientes.push(response.data);
		                      bus.$emit('alert', 'Asistente Agregado','success');
		                      this.clear();
		                      this.errors = [];
		                    }
		                   this.loader = false;
		              }, (response) => {
		                  console.log('error al guardar asistente')
		                  this.loader = false;
		                   this.errors = response.data;
		              });
		        
		            }

		      	},//save
	         getOffices(){
              this.$http.get('/medic/account/consultorios')
                  .then(resp => {
                    

                     this.offices = resp.data
                 
                 

                  });
           }

       
          
       },
       created(){

       	    console.log('Component ready. PatienForm');


             if(this.clinics){
                this.offices = this.clinics;
             }

           bus.$on('editAssistant', this.edit);
           bus.$on('newConsultorioIndependiente', this.getOffices);
           bus.$on('deleteConsultorioIndependiente', this.getOffices);
           
       }
    }
</script>