<template>
	<div class="form-horizontal">
      <div class="form-group" v-if="paciente.IDhash">
        <label for="name" class="col-sm-2 control-label">ID</label>

        <div class="col-sm-10">
           <label for="name" class="control-label">{{ paciente.id }}</label>  
        </div>
      </div>
      <div class="form-group">
        <label for="paciente_name" class="col-sm-2 control-label">Nombre</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="first_name" placeholder="Nombre del paciente" v-model="paciente.first_name" >
          <form-error v-if="errors.first_name" :errors="errors" style="float:right;">
              {{ errors.first_name[0] }}
          </form-error>
          </div>
      </div>
      <div class="form-group">
        <label for="paciente_address" class="col-sm-2 control-label">Apellidos</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="last_name" placeholder="Apellidos"  v-model="paciente.last_name">
          <form-error v-if="errors.last_name" :errors="errors" style="float:right;">
              {{ errors.last_name[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="paciente_birth_date" class="col-sm-2 control-label">Fecha de Nacimiento</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="birth_date" placeholder="dd/mm/yyyy"  v-model="paciente.birth_date">
          <form-error v-if="errors.birth_date" :errors="errors" style="float:right;">
              {{ errors.birth_date[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="paciente_province" class="col-sm-2 control-label">Sexo</label>

        <div class="col-sm-10">
          <select class="form-control " style="width: 100%;" name="gender" placeholder="-- Selecciona Sexo --"  v-model="paciente.gender">
            <option></option>
            <option v-for="item in genders" v-bind:value="item.value"> {{ item.text }}</option>
            
          </select>
          <form-error v-if="errors.gender" :errors="errors" style="float:right;">
              {{ errors.gender[0] }}
          </form-error>
        </div>
      </div>

      <div class="form-group">
        <label for="paciente_phone" class="col-sm-2 control-label">Teléfono</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="phone" placeholder="Celular" v-model="paciente.phone">
          <form-error v-if="errors.phone" :errors="errors" style="float:right;">
              {{ errors.phone[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="paciente_phone" class="col-sm-2 control-label">Email</label>

        <div class="col-sm-10">
          <input type="email" class="form-control" name="email" placeholder="Email" v-model="paciente.email">
          <form-error v-if="errors.email" :errors="errors" style="float:right;">
              {{ errors.email[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="paciente_address" class="col-sm-2 control-label">Dirección</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="address" placeholder="Dirección"  v-model="paciente.address">
          <form-error v-if="errors.address" :errors="errors" style="float:right;">
              {{ errors.address[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="paciente_province" class="col-sm-2 control-label">Provincia</label>

        <div class="col-sm-10">
          <select class="form-control " style="width: 100%;" name="province" placeholder="-- Selecciona provincia --"  v-model="paciente.province">
            <option></option>
            <option v-for="item in provincias" v-bind:value="item"> {{ item }}</option>
            
          </select>
          <form-error v-if="errors.province" :errors="errors" style="float:right;">
              {{ errors.province[0] }}
          </form-error>
        </div>
      </div>
      <div class="form-group">
        <label for="paciente_city" class="col-sm-2 control-label">Ciudad</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="city" placeholder="Ciudad" v-model="paciente.city" >
          <form-error v-if="errors.city" :errors="errors" style="float:right;">
              {{ errors.city[0] }}
          </form-error>
        </div>
      </div>
      <!--<div class="form-group">
        <label for="paciente_conditions" class="col-sm-2 control-label">Padecimientos</label>

        <div class="col-sm-10">
          <input type="text" class="form-control" name="conditions" placeholder="Padecimientos" v-model="paciente.conditions" >
          <form-error v-if="errors.conditions" :errors="errors" style="float:right;">
              {{ errors.conditions[0] }}
          </form-error>
        </div>
      </div>-->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-success" @click="save()">Guardar</button>
          <button type="submit" class="btn btn-danger" @click="cancel()">Cancelar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
        </div>
      </div>
	</div>
</template>
<script>
	import FormError from './FormError.vue';

    export default {
        props: {
		    fromModal: {
		      type: Boolean,
		      default: false
		    },
        url:{
          type:String,
          default: '/account/patients'
        }
		},
        data () {
	        return {
	 
	          provincias: ['Guanacaste','San Jose','Heredia','Limon','Cartago','Puntarenas','Alajuela'],
	          genders: [
	            {
	              text:'Masculino',
	              value: 'm'
	            },
	            {
	              text:'Femenino',
	              value: 'f'
	            },

	          ],
	          loader:false,
	          //fromModal: false,
	          paciente: {},
	          errors: []
	         
	        
	          
	        }
	      },
	      components:{
	        FormError
	       
	      },
        methods: {

        		cancel() {
		          this.paciente = {};
		          bus.$emit('cancelNewPatient'); 
		        
		        },
        
		        edit(paciente){

		        	this.paciente = paciente;
		        },
		         
		        save() {
              this.loader = true;
		          //var resource = this.$resource('/medic/account/offices');
		           if(this.paciente.id)
		           {
		             var resource = this.$resource(this.url +'/'+ this.paciente.id);

		                resource.update(this.paciente).then((response) => {
		                    
		                     bus.$emit('alert', 'Paciente Actualizado','success');
		                     //bus.$emit('updateList', 'Paciente Actualizado','success');
		                     this.loader = false;
		                     this.errors = [];
		                     this.paciente = {};
		                }, (response) => {
		                    console.log(response.data)
		                    this.loader = false;
		                    this.loader_message ="Error al guardar cambios";
		                    this.errors = response.data.errors;
		                });

		           }else{
		              this.$http.post(this.url, this.paciente).then((response) => {
		                    console.log(response.status);
		                    console.log(response.data);
		                    if(response.status == 200 && response.data)
		                    {
		                      if(this.fromModal)
		                      {
		                      	bus.$emit('selectedPatient', response.data); 
		                      }
		                      bus.$emit('addToList', response.data); //this.pacientes.push(response.data);
		                      bus.$emit('alert', 'Paciente Agregado','success');
		                      this.paciente = {};
		                      this.errors = [];
		                    }
		                   this.loader = false;
		              }, (response) => {
                    
		                  console.log('error al guardar paciente')
		                  this.loader = false;
		                   this.errors = response.data.errors;
		              });
		        
		            }

		      	}//save
	     

       
          
       },
       created(){

       	    console.log('Component ready. PatienForm');

           bus.$on('editPatient', this.edit);
       }
    }
</script>