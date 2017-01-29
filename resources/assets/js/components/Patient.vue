<template>
<div>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        <div class="modal-header">
        
          <h4 class="modal-title" id="myModalLabel">Confirmacion de la cita</h4>
        </div>
        <div class="modal-body">
            
            
           <div class="box box-widget widget-user-2" v-show="!newPatient" v-bind:data-patient="paciente.id " v-bind:data-title=" paciente.first_name ">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-green" >
                <div class="widget-user-image">
                  <!-- <img class="profile-user-img img-responsive img-circle" src="{{ (Storage::disk('public')->exists('patients/'.auth()->user()->patients->first()->id.'/photo.jpg')) ? Storage::url('patients/'.auth()->user()->patients->first()->id.'/photo.jpg') : Storage::url('avatars/default-avatar.jpg') }}" alt="User profile picture"> -->
                  <!-- <img class="profile-user-img img-responsive img-circle" v-bind:src="'/storage/avatars/'+ paciente.id +'/avatar.jpg'" alt="User profile picture"> -->
                  <img class="profile-user-img img-responsive img-circle" v-bind:src="'/storage/avatars/default-avatar.jpg'" alt="User profile picture">  
                 
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
          <div class="form-horizontal" v-show="newPatient">
              <div class="callout callout-info"><h4>Información !</h4> <p>Agrega un paciente nuevo o selecciona uno de la lista de abajo.</p></div>
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
                <label for="paciente_email" class="col-sm-2 control-label">Email</label>

                <div class="col-sm-10">
                  <input type="text" class="form-control" name="email" placeholder="Email" v-model="paciente.email">
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
               

             
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-success" @click="save()">Guardar</button>
                  <button type="submit" class="btn btn-danger" @click="cancel()">Cancelar</button>
                </div>
              </div>


              <h3>Tus pacientes</h3>
              <ul id="patients-list" class="todo-list ui-sortable" v-show="pacientes.length">
               
                <li v-for="item in pacientes">
                  <!-- todo text -->
                  <a href="#"><i class="fa fa-user"></i><span><span class="text" @click="select(item)"> {{ item.first_name }} {{ item.last_name }} - {{ item.email }}</span></span></a>
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <!-- <i class="fa fa-edit" @click="edit(item)"></i> -->
                    <!-- <i class="fa fa-trash-o delete" @click="remove(item)"></i> -->
                  </div>
                </li>
               
              </ul>
          </div>
               
            
             
             
        </div>
         <div class="modal-footer" v-show="!newPatient">
         
          <button type="button" class="btn btn-default pull-left btn-cancelar-cita" data-dismiss="modal" data-appointment="">Cancelar</button>
          <button type="button" class="btn btn-primary btn-finalizar-cita" data-appointment="">Finalizar</button>
          <button type="button" class="btn btn-primary btn-close-cita" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  
  
</div>
</template>

<script>
    import FormError from './FormError.vue';

    export default {

      props:['patient','patients'],
      
      data () {
        return {
          paciente:{},
          pacientes:[],
          loader:false,
          newPatient:false,
          editPhone:false,
          editEmail:false,
          newPatient:false,
          oldPhone: '',
          oldEmail: '',
          errors: [],
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
          provincias: ['Guanacaste','San Jose','Heredia','Limon','Cartago','Puntarenas','Alajuela'],
         
        
          
        }
      },
      components:{
        FormError
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

      },
	     

     
    

      },
      created () {
             console.log('Component ready. office')

             this.paciente = this.patient;
             this.oldEmail = this.patient.email;
             this.oldPhone = this.patient.phone;
             this.pacientes = this.patients;
            
        }
    }
</script>