<template>
<div>
  
        <div v-show="!newPatient" class="widget-user-2" v-bind:data-patient="(paciente) ? paciente.id : '' " v-bind:data-title=" (paciente) ? paciente.first_name : '' ">
          <patient-search :patient="paciente"></patient-search>
          <div class="form-group">
            <input id="new-event" type="text" class="form-control" placeholder="Motivo de la cita" data-modaldate>
          </div>
           <a href="#" @click="nuevo()" class="">o Crear un paciente?</a>
           <div class="form-group">
              <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Agregar</button>
            </div>
        </div>
           
          <div v-show="newPatient">
              <div class="callout callout-info"><h4>Información !</h4> <p>Agrega un paciente nuevo</p></div>
              <patient-form :fromModal="true"></patient-form>
     
          </div>
              
  
</div>
</template>

<script>
    import PatientSearch from './PatientSearch.vue';
    import PatientForm from './PatientForm.vue';
    //import Select2 from './Select2.vue';
    
    
    export default {
      
      props:['patient'],
      
      data () {
        return {
          paciente:null,
          loader:false,
          newPatient:false,
          selectedPatient: 0,
          options: []
        }
      },
  
      methods: {
       
        nuevo(){
          this.newPatient = true;
          //this.paciente = {};
        },
  
        cancel() {
          //this.paciente = this.patient;
          this.newPatient = false;
        
        },
    
        select(patient) {
  
          if(patient){
            this.paciente = patient;
            this.selectedPatient = patient.id;
            
          }else{
              this.selectedPatient = 0;
              this.paciente = null;
          }

          this.newPatient = false;
        
        },
        selectedPatientRecentlyCreated(patient){
          
            if(patient)
            {
              this.paciente = patient;
              this.selectedPatient = patient.id;
              bus.$emit('selectedPatientToSelect', patient);
            }else
            {
              this.selectedPatient = 0;
              this.paciente = null;
            }
            this.newPatient = false;
            
        }
        

      }, //methods
      created () {
             console.log('Component ready. Appointment create')
             
             bus.$on('patientCreated', this.selectedPatientRecentlyCreated);
             bus.$on('selectedPatient', this.select);
             bus.$on('cancelNewPatient', this.cancel);
            
             if(this.patient){
                this.paciente = this.patient;
             }

           

           

            
        }
    }
</script>