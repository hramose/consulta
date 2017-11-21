<template>
<div>
  <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
          <h4 class="modal-title" id="myModalLabel">Crear cita</h4>
        </div>
        <div class="modal-body" data-modaldate data-modaldate-end data-office data-officename>
            
            <div class="box box-widget widget-user-2"  v-show="!newPatient" v-bind:data-patient="(paciente) ? paciente.id : '' " v-bind:data-title=" (paciente) ? paciente.first_name : '' " v-bind:data-office="(office) ? office.id : '' ">
               <patient-search :patient="paciente"></patient-search>
               <!-- <div class="form-group">
                  <v-select :debounce="250" :on-search="getOffices"  :options="offices" placeholder="Selecciona el consultorio para la cita..." label="name" :on-change="selectOffice" :value.sync="office"></v-select>
               </div> -->
               <div class="form-group">
                  <input id="modal-new-event" type="text" class="form-control" placeholder="Motivo de la cita" data-modaldate data-modaldate-end>
              </div>
               <a href="#" @click="nuevo()" class="">o Crear un paciente?</a>
            </div>
           
          <div v-show="newPatient">
              <div class="callout callout-info"><h4>Información !</h4> <p>Agrega un paciente nuevo</p></div>
              <patient-form :fromModal="true" url="/medic/account/patients"></patient-form>
     
          </div>
               
            
             
             
        </div>
         <div class="modal-footer" v-show="!newPatient">
         
          <button type="button" class="btn btn-default pull-left btn-cancelar-cita" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-info btn-iniciar-cita">Iniciar consulta</button>
          <button type="button" class="btn btn-success btn-finalizar-cita">Crear cita</button>
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
      
      props:['patient'],
      components: {vSelect},
      data () {
        return {
          paciente:null,
          loader:false,
          newPatient:false,
          selectedPatient: 0,
          options: [],
          offices: [],
          office:null

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
            
        },
        getOffices(search, loading) {
         
            loading(true)
           
           let queryParam = {
                ['q']: search
              }
            this.$http.get('/medic/account/offices/list', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               
               this.offices = resp.data
               loading(false)
            })
            
          },
          selectOffice(clinica) {
            
            if(clinica){
              this.office = clinica;
              /*this.appointment.title = clinica.name;
              this.appointment.office_info = JSON.stringify(clinica);*/
                
              
            }

          
          
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