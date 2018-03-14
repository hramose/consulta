<template>
<div>
  <div class="modal fade" id="setupSchedule" tabindex="-1" role="dialog" aria-labelledby="setupSchedule">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
          <h4 class="modal-title" id="setupScheduleLabel">Programando tu agenda</h4>
        </div>
        <div class="modal-body" data-modaldate>
            
            <div class="form-group">
              <v-select :debounce="250" :on-search="getOptions"  :options="options" placeholder="Buscar Clinica o Hospitales..." label="name" :on-change="select" :value.sync="clinica"></v-select>
            </div>
            <div class="row">
              <div class="col-xs-4">
                <div class="form-group">
                    <label>Fecha:</label>

                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                     
                      <input type="text" class="form-control pull-right"  name="date" id="datetimepicker1" v-model="appointment.date">
                    </div>
                    <!-- /.input group -->
                  </div>
              </div>
               <div class="col-xs-3">
               <div class="form-group">
                    <label>Hora de inicio:</label>

                    <div class="input-group">
                      <input type="text" class="form-control " id="datetimepicker2" v-model="appointment.hourini">

                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                    <!-- /.input group -->
                  </div>
              </div>
                <div class="col-xs-3">
                <div class="form-group">
                    <label>Hora de fin:</label>

                    <div class="input-group">
                      <input type="text" class="form-control " id="datetimepicker3" v-model="appointment.hourfin">

                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                    </div>
                   
                  </div>
              </div> -
            </div>
            <button type="button" class="btn btn-primary add-cita" @click="add">Agregar a agenda</button>
           
             
        </div>
         <div class="modal-footer" v-show="!newPatient">
         
          <button type="button" class="btn btn-default pull-left btn-cancelar-cita" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-finalizar-cita">Guardar</button>
          <button type="button" class="btn btn-primary btn-close-cita" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  
  
</div>
</template>

<script>
    import vSelect from 'vue-select';
    import FormError from './FormError.vue';
  

    export default {

      props:[],
      components: {vSelect, FormError},
     
      data () {
        return {
          result1: null,
          clinica:null,
          appointment : {
            title : '',
            date : '',
            start : '',
            end : '',
            backgroundColor: '#00a65a', //Success (green)
            borderColor: '#00a65a',
            patient_id: 0,
            office_info: '',
            allDay: 0,
            hourini:'',
            hourfin:''
            
          },
          loader:false,
          options: [],
          errors:[]
          
  
        }
      },
    
      methods: {
        add(){
           
           this.appointment.start = this.appointment.date + 'T'+ this.appointment.hourini;
           this.appointment.end = this.appointment.date + 'T'+ this.appointment.hourfin
           console.log(this.appointment);
           this.$http.post('/medic/appointments', this.appointment).then((response) => {
                    console.log(response.status);
                    console.log(response.data);
                    if((response.status == 200 || response.status == 201) && response.data)
                    {
                      //this.appointment = response.data;
                      this.errors = [];
                       bus.$emit('alert', 'Evento agregado','success');
                    }
                   this.loader = false;
              }, (response) => {
                  console.log('error al guardar')
                  this.loader = false;
                  this.errors = response.data;
              });
        },
        getOptions(search, loading) {
         
            loading(true)
           
           let queryParam = {
                ['q']: search
              }
            this.$http.get('/medic/account/offices/list', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               
               this.options = resp.data
               loading(false)
            })
            
          },
          select(clinica) {
            
            if(clinica){
              this.clinica = clinica;
              this.appointment.title = clinica.name;
              this.appointment.office_info = JSON.stringify(clinica);
                
              
            }

          
          
           },
        
        save(){


        }

      
	   

      }, //methods
      created () {
             console.log('Component ready. wizard Schedule')
            
             
          
             
            
        }
    }
</script>
