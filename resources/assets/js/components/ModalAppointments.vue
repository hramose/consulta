<template>
<div>
  <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
          <h4 class="modal-title" id="myModalLabel"  v-show="!showPackages && !showPendingPayment">Crear cita</h4>
          <h4 v-show="showPackages">Parece que no tienes una <b>subscripción</b> todavia! Selecciona una para continuar con el proceso</h4>
           <h4 v-show="showPendingPayment">Tienes pagos pendientes!. Haz el pago para poder continuar</h4>
        </div>
        <div class="modal-body" data-modaldate data-modaldate-end data-office data-officename>
            
            <div class="box box-widget widget-user-2"  v-show="!newPatient && !showPackages && !showPendingPayment" v-bind:data-patient="(paciente) ? paciente.id : '' " v-bind:data-title=" (paciente) ? paciente.first_name : '' " v-bind:data-office="(office) ? office.id : '' ">
               <patient-search :patient="paciente"></patient-search>
               <!-- <div class="form-group">
                  <v-select :debounce="250" :on-search="getOffices"  :options="offices" placeholder="Selecciona el consultorio para la cita..." label="name" :on-change="selectOffice" :value.sync="office"></v-select>
               </div> -->
               <div class="form-group">
                  <input id="modal-new-event" type="text" class="form-control" placeholder="Motivo de la cita" data-modaldate data-modaldate-end>
              </div>
               <a href="#" @click="nuevo()" class="">o Crear un paciente?</a>
            </div>
           
          <div v-show="newPatient && !showPackages">
              <div class="callout callout-info"><h4>Información !</h4> <p>Agrega un paciente nuevo</p></div>
              <patient-form :fromModal="true" url="/medic/account/patients"></patient-form>
     
          </div>
          <div class="pending-payment text-center" v-show="showPendingPayment">
           
           <table-pending-payments :monthlyCharges="monthlyCharges" token="token"></table-pending-payments>
        
            
           
          </div>
          <div class="text-center" v-show="showPackages">
               <table-subscriptions></table-subscriptions>
          </div>
          
         
               

             
        </div>
         <div class="modal-footer" v-show="!newPatient">
         
          <button type="button" class="btn btn-default pull-left btn-cancelar-cita" data-dismiss="modal" @click="showPackages = false; showPendingPayment = false; ">Cancelar</button>
          <button type="button" class="btn btn-info btn-iniciar-cita" v-if="has_subscription && !monthlyCharges.length">Iniciar consulta</button>
          <button type="button" class="btn btn-info btn-show-package" v-if="!has_subscription" @click="showPackages = true">Iniciar consulta</button>
          <button type="button" class="btn btn-info btn-show-pending-payment"v-if="monthlyCharges.length" @click="showPendingPayment = true">Iniciar consulta</button>
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
    import TableSubscriptions from './TableSubscriptions.vue';
    import TablePendingPayments from './TablePendingPayments.vue';
    //import Select2 from './Select2.vue';
    
    
    export default {
      
      props:['patient','has_subscription','pending_payment','pending_payment_total','token'],
      components: {vSelect},
      data () {
        return {
          paciente:null,
          loader:false,
          newPatient:false,
          selectedPatient: 0,
          options: [],
          offices: [],
          office:null,
          subscriptionsPackages: [],
          monthlyCharges: [],
          showPackages:false,
          showPendingPayment:false

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

            

             this.monthlyCharges = this.pending_payment;
             
             bus.$on('patientCreated', this.selectedPatientRecentlyCreated);
             bus.$on('selectedPatient', this.select);
             bus.$on('cancelNewPatient', this.cancel);
            
             if(this.patient){
                this.paciente = this.patient;
             }

           

           

            
        }
    }
</script>