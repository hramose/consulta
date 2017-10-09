<template>
<div>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
          <h4 class="modal-title" id="myModalLabel">Confirmacion de la cita</h4>
        </div>
        <div class="modal-body" data-modaldate>
            
             <p> Si lo desea puede confirmar su cita al teléfono: <a :href="'tel:'+ phone" class="btn btn-success btn-xs"><i class="fa fa-phone" :title="phone"></i> {{ phone }}</a></p>
           <div class="box box-widget widget-user-2"  v-show="!newPatient" v-bind:data-patient="paciente.id " v-bind:data-title=" paciente.first_name ">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-green" >
                <div class="widget-user-image">
                  
                  <img class="profile-user-img img-responsive img-circle" v-bind:src="'/img/default-avatar.jpg'" alt="User profile picture">  
                 
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{ paciente.first_name }} {{ paciente.last_name }}</h3>
                <h5 class="widget-user-desc">{{ (paciente.gender == 'm') ? 'Masculino' : 'Femenino' }}</h5>
              </div>
              <div class="box-footer no-padding">
                
                  
                
                  
                      <ul class="nav nav-stacked">
                        
                        <li><a href="#"><div v-show="!editPhone">Teléfono: {{ paciente.phone }} <span class="pull-right" @click="editPhone = true"> <i class="fa fa-edit"></i> Editar</span></div> <div v-show="editPhone"><input type="text" class="form-control" name="phone" placeholder="Teléfono" v-model="paciente.phone"> <button @click="save()">Guardar</button><button @click="cancelLine()">Cancelar</button></div> </a></li>
                        <li><a href="#"><div  v-show="!editEmail">Email: {{ paciente.email }} <span class="pull-right" @click="editEmail = true"> <i class="fa fa-edit"></i> Editar</span></div><div v-show="editEmail"><input type="text" class="form-control" name="email" placeholder="Email" v-model="paciente.email"> <button @click="save()">Guardar</button><button @click="cancelLine()">Cancelar</button></div> </a></li>
                      
                      </ul>
                 
                
                <a href="#" @click="nuevo()" class="">Es un paciente distinto?</a>
              </div>
             
             
          </div>  
          <div v-show="newPatient">
              <div class="callout callout-info"><h4>Información !</h4> <p>Agrega un paciente nuevo o selecciona uno de la lista de abajo.</p></div>
              <patient-form :fromModal="true"></patient-form>
  
              <h3>Tus pacientes</h3>
              <patient-list :patients="patients" :fromModal="true"></patient-list>
             
          </div>
               
            
             
             
        </div>
         <div class="modal-footer" v-show="!newPatient">
         
          <button type="button" class="btn btn-default pull-left btn-cancelar-cita" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-finalizar-cita">Crear cita</button>
          <button type="button" class="btn btn-primary btn-close-cita" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  
  
</div>
</template>

<script>

    import PatientForm from './PatientForm.vue';
    import PatientList from './PatientList.vue';
    
    export default {

      props:['patient','patients','phone'],
      
      data () {
        return {
          paciente:{},
          loader:false,
          newPatient:false,
          editPhone:false,
          editEmail:false,
          newPatient:false,
          oldPhone: '',
          oldEmail: ''
          
  
        }
      },
  
      methods: {
        nuevo(){
          this.newPatient = true;
          this.paciente = {};
        },
        cancelLine() {
         
          this.editPhone = false;
          this.editEmail = false;
          this.paciente.phone = this.oldPhone;
          this.paciente.email = this.oldEmail;
        
        },
        cancel() {
          this.paciente = this.patient;
          this.newPatient = false;
        
        },
        edit(patient) {

          this.paciente = patient;
        
        },
        select(patient) {

          this.paciente = patient;
          this.newPatient = false;
        
        },
        save() {

          //var resource = this.$resource('/medic/account/offices');
           this.loader = true;
           if(this.paciente.id)
           {
             var resource = this.$resource('/patients/'+ this.paciente.id);

                resource.update(this.paciente).then((response) => {
                     this.loader = false;
                     this.errors = [];
                     this.paciente = response.data;
                     this.oldEmail = this.paciente.email;
                     this.oldPhone = this.paciente.phone;
                     this.editPhone = false;
                     this.editEmail = false;
                }, (response) => {
                    console.log(response.data)
                    this.loader = false;
                    this.loader_message ="Error al guardar cambios";
                    this.errors = response.data;
                });

           }else{
              this.$http.post('/patients', this.paciente).then((response) => {
                    console.log(response.status);
                    console.log(response.data);
                    if(response.status == 200 && response.data)
                    {
                      this.paciente = response.data;
                      this.errors = [];
                      this.newPatient = false;
                    }
                   this.loader = false;
              }, (response) => {
                  console.log('error al guardar')
                  this.loader = false;
                  this.errors = response.data;
              });
        
            }

      }//save
	   

      }, //methods
      created () {
             console.log('Component ready. office')
             
             bus.$on('selectedPatient', this.select);
             bus.$on('cancelNewPatient', this.cancel);

             this.paciente = this.patient;
             this.oldEmail = this.patient.email;
             this.oldPhone = this.patient.phone;
             
            
        }
    }
</script>