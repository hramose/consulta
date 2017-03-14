<template>
	<div>
		<ul id="patients-list" class="todo-list ui-sortable" >
       
	        <li v-for="item in pacientes">
	          <!-- todo text -->
	          <a href="#"><i class="fa fa-user"></i> ID: {{ item.id }} <span><span class="text" @click="edit(item)"> {{ item.first_name }} {{ item.last_name }}</span></span></a>
	          <!-- General tools such as edit or delete-->
	          <div class="tools" v-show="!fromModal">
	            <i class="fa fa-edit" @click="edit(item)"></i>
	            <i class="fa fa-trash-o delete" @click="remove(item)"></i>
	          </div>
	        </li>
	       
	      </ul>
	</div>
</template>
<script>

 export default {
        //props: ['patients','fromModal'],
         props: {
         	patients: Array,
		    fromModal: {
		      type: Boolean,
		      default: false
		    }
		},
        data () {
	        return {
	 
	          pacientes:[],
	          loader:false,
	         
	          
	        }
	      },
	      
        methods: {
	         edit(patient) {
			  
			  if(this.fromModal)
              {
              	bus.$emit('selectedPatient', patient); 
              }else{
	          	bus.$emit('editPatient', patient);
	          }
	        },//edit

	        remove(item){
	           

	            this.$http.delete('/account/patients/'+item.id).then((response) => {
	                  
	                  if(response.status == 200 && response.data == 'ok')
	                  {
	                     var index = this.pacientes.indexOf(item)
	                    this.pacientes.splice(index, 1);
	                    bus.$emit('alert', 'Paciente Eliminado','success');
	                  }else{
	                      
	                      bus.$emit('alert', 'No se puede eliminar paciente por que tiene citas asignadas','danger');

	                  }

	              }, (response) => {
	                  
	                   bus.$emit('alert', 'Error al eliminar el paciente', 'danger');
	                  this.loader = false;
	              });


	          }, //remove

          	addToList(patient){

          		this.pacientes.push(patient);
          	}//addToList

       	
        },//methods
        created() {
          
          this.pacientes = this.patients;
	     
	      bus.$on('addToList', this.addToList);
	      
	    }
    }
</script>