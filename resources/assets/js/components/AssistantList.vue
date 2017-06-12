<template>
	<div>
		<ul id="patients-list" class="todo-list ui-sortable" >
       
	        <li v-for="item in asistentes">
	          <!-- todo text -->
	          <a href="#"><i class="fa fa-user"></i> ID: {{ item.id }} <span><span class="text" @click="edit(item)"> {{ item.name }} </span></span></a>
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
         	assistants: Array,
		    fromModal: {
		      type: Boolean,
		      default: false
		    },
		     url:{
	          type:String,
	          default: '/medic/account/assistants'
	        }
		},
        data () {
	        return {
	 
	          asistentes:[],
	          loader:false,
	         
	          
	        }
	      },
	      
        methods: {
	         edit(assistant) {
			  
			  
	          	bus.$emit('editAssistant', assistant);
	        
	        },//edit

	        getAssistants(){

	        	this.$http.get(this.url)
		            .then(resp => {
		              

		               this.asistentes = resp.data
		           
		           

		            });

	        },
	        remove(item){
	           
	        	this.loader = true;
	            this.$http.delete(this.url +'/'+item.id).then((response) => {
	                  
	                  if(response.status == 200 && response.data == 'ok')
	                  {
	                     var index = this.asistentes.indexOf(item)
	                    this.asistentes.splice(index, 1);
	                    bus.$emit('alert', 'Asistente Eliminado','success');
	                  }
	                  this.loader = false;

	              }, (response) => {
	                  
	                   bus.$emit('alert', 'Error al eliminar el paciente', 'danger');
	                  this.loader = false;
	              });


	          }, //remove

          	addToList(assistant){

          		this.asistentes.push(assistant);
          	}//addToList

       	
        },//methods
        created() {
          
          this.asistentes = this.assistants;
	     
	      bus.$on('addToAssistantList', this.addToList);
	       bus.$on('updateAssistantList', this.getAssistants);
	      
	    }
    }
</script>