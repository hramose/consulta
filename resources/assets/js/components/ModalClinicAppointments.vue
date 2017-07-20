<template>
<div>
  <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
          <h4 class="modal-title" id="myModalLabel">Crear cita</h4>
        </div>
        <div class="modal-body" data-modaldate data-modaldate-end>
            
            <div class="box box-widget widget-user-2"  v-show="!newPatient" v-bind:data-patient="(paciente) ? paciente.id : '' " v-bind:data-title=" (paciente) ? paciente.first_name : '' " v-bind:data-office="(office) ? office : '' " >
               <patient-search :patient="paciente" :url="'/clinic/patients/list'"></patient-search>
                <div class="form-group" v-show="!isPatient">
                  <p>Este paciente no esta asociado al medico. Asignalo agregando el ID del paciente para confirmar</p>
                  <input id="modal-patient-id" type="text" class="form-control" v-model ="patientId" placeholder="ID" @keyup="verifyId()">
                  <span class="label label-danger"  v-show="!validated && (patientId && selectedPatient)">ID incorrecto</span>
                </div>
               <div class="form-group">
                  <input id="modal-new-event" type="text" class="form-control" placeholder="Motivo de la cita" data-modaldate data-modaldate-end>
              </div>
               <a href="#" @click="nuevo()" class="">o Crear un paciente?</a>
            </div>
           
          <div v-show="newPatient">
              <div class="callout callout-info"><h4>Información !</h4> <p>Agrega un paciente nuevo</p></div>
              <patient-form :fromModal="true" ></patient-form>
     
          </div>
               
            
             
             
        </div>
         <div class="modal-footer" v-show="!newPatient">
         
          <button type="button" class="btn btn-default pull-left btn-cancelar-cita" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-finalizar-cita" :disabled="!validated">Crear cita</button>
          <button type="button" class="btn btn-primary btn-close-cita" data-dismiss="modal">Cerrar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
        </div>
      </div>
    </div>
  </div>
  
  
</div>
</template>

<script>
    import vSelect from 'vue-select'
    import PatientSearch from './PatientSearch.vue';
    import PatientForm from './PatientForm.vue';
    //import Select2 from './Select2.vue';
    
    
    export default {
      
      props:['patient','fromClinic','office'],
      components: {vSelect},
      data () {
        return {
          paciente:null,
          loader:false,
          newPatient:false,
          selectedPatient: 0,
          options: [],
          isPatient: true,
          patientId:'',
          validated: false
         
         

        }
      },
  
      methods: {
         verifyId(){
          
          if(this.selectedPatient == this.patientId)
          {
            this.validated = true;
          }else
            this.validated = false;
          //this.paciente = {};
        },
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
            
             let queryParam = {
                ['patient_id']: patient.id,
                ['medic_id']: $('.modal-body').attr('data-medic')
              }
             this.$http.get('/clinic/patients/verify', {params: Object.assign(queryParam, this.data)})
                .then(resp => {
                   //alert('reporte')
                   console.log(resp.data);

                  if(resp.data == 'yes')
                   { 
                    this.isPatient = true;
                    this.validated = true;
                   }
                  else{
                    this.isPatient = false;
                    this.validated = false;
                  }

                  this.paciente = patient;
                  this.selectedPatient = patient.id;
                });

            
            
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
            
        },
       
        

      }, //methods
      created () {
             console.log('Component ready. Modal Appointments')
             
             bus.$on('patientCreated', this.selectedPatientRecentlyCreated);
             bus.$on('selectedPatient', this.select);
             bus.$on('cancelNewPatient', this.cancel);
            
             if(this.patient){
                this.paciente = this.patient;
             }

           

           

            
        }
    }
</script>