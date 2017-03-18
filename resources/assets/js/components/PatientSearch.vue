<template>
	<div>
		<div class="form-group">
          <v-select :debounce="250" :on-search="getOptions"  :options="options" placeholder="Search Pacientes..." label="fullname" :on-change="select" :value.sync="paciente"></v-select>
        </div>
        
                
	</div>
</template>
<script>
	import vSelect from 'vue-select'

 export default {
        props: ['patient'],
        components: {vSelect},
         
        data () {
	        return {
	 
	          paciente:null,
          	  loader:false,
              /*newPatient:false,
              selectedPatient: 0,*/
              options: []
	         
	          
	        }
	      },
	      
        methods: {
	        getOptions: _.debounce(function(search,loading) {
	           
		      loading(true)
	         
	         let queryParam = {
	              ['q']: search
	            }
	          this.$http.get('/medic/patients/list', {params: Object.assign(queryParam, this.data)})
	          .then(resp => {
	             
	             this.options = resp.data.data
	             loading(false)
	          })

		    }, 500),
		    
	        select(patient) {
  
	          if(patient){
	            this.paciente = patient;
	            
	          }

	         bus.$emit('selectedPatient', patient);
	        
	         },

          	selectedPatient(patient){

          		if(patient){
		            this.paciente = patient;
		            
		          }
          	}//addToList

       	
        },//methods
        created() {
          
          if(this.patient)
         	 this.paciente = this.patient;
	     
	      bus.$on('selectedPatientToSelect', this.selectedPatient);
	      
	    }
    }
</script>