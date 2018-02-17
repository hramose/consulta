<template>
	<li class="dropdown notifications-menu" title="Notificaciones de consultas">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" v-show="total()"> 
                            {{ total() }} 
                  
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes {{ total() }} nueva(s) cita(s) reservada(s)</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  
                  <li v-for="appointment in citas" class="appointment-li">
                    <a href="#">
                        
                        <h5><span @click="viewed(appointment)" v-show="!read" class="pull-left">  <i class="fa fa-trash"></i>   </span> <span @click="toggleDetails(appointment)" style="padding: 2rem 20px;"> {{ (appointment.patient) ? appointment.patient.first_name : 'Paciente eliminado' }} -  {{ formatDate(appointment.start) }}</span>    </h5>
                      
                    </a>
                    
                  </li>
                 
                
                </ul>
              </li>
            
              <li class="footer"><a :href="url">Ver todas</a></li>
              <div v-show="showDetails" class="preview-notification-details">

                  <div class="box box-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    
                    <div class="bg-teal widget-user-header " >
                       
                        <div class="widget-user-image">
                        
                        <img class="profile-user-img img-responsive img-circle" v-bind:src="appointmentSelected.patient.photo" alt="User profile picture">  
                        
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">
                            {{ appointmentSelected.patient.first_name }} {{  appointmentSelected.patient.last_name }}</h3>
                        <h5 class="widget-user-desc">{{ (appointmentSelected.patient.gender == 'm') ? 'Masculino' : 'Femenino' }}</h5>
                    </div>
                    <div class="box-footer no-padding">
                        
                        
                        
                        
                            <ul class="nav nav-stacked">
                                
                                <li><a href="#"><div style="color:#444;">Teléfono: {{ appointmentSelected.patient.phone }} </div> </a></li>
                                <li><a href="#"><div style="color:#444;">Email: {{ appointmentSelected.patient.email }} </div></a></li>
                                 <li><a href="#"><div style="color:#444;">Cita con médico: {{ appointmentSelected.user.name }}</div></a></li>
                             
                            </ul>
                        
                        
                        
                    </div>
                    
                    
                    
                </div>  
              </div>
            </ul>
    </li>
</template>
<script>

 export default {
        //props: ['patients','fromModal'],
         props: {
             userId: {
                type:Number,
                default: 0
             },
             officeId: {
                type:Number,
                default: 0
             },
             appointments: Array,
             url:{
                type:String,
                default: '/medic/appointments'
            },
             read:{
                type:Boolean,
                default: false
            },
            view_assistant:{
                type:Boolean,
                default: false
            }
         	
		    
		},
        data () {
	        return {
	 
	          citas:[],
	          loader:false,
              appointmentSelected:{
                  user:{},
                  patient:{}
              },
              showDetails:false
             
	          
	        }
	      },
	      
        methods: {
            formatDate(date){
               return moment(date).format("YYYY-MM-DD HH:mm");
           },
            total(){
                return this.citas.length;
            },
            toggleDetails(item){
               
                if(item.patient){
              
                    if(this.appointmentSelected == item)
                        this.showDetails = !this.showDetails
                    else{
                        this.showDetails = true;
                    }
                    this.appointmentSelected = item
                }

                
            },
	        viewed(item){
               

                var resource = this.$resource(this.url +'/'+ item.id +'/viewed');

                resource.update().then((response) => {
                    
                     
                       this.loader = false;
                       var index = this.citas.indexOf(item)
                        this.citas.splice(index, 1);
                        this.showDetails = false;
                        this.appointmentSelected = {
                             user:{},
                             patient:{}
                        };
                     
                }, (response) => {
                    console.log(response.data)
                    this.loader = false;
                   
                });

               
            },
            listen(){
              var audio = new Audio('/img/notification.mp3');
           
              if(this.userId){

                Echo.private(`users.${this.userId}.notifications`)
                    .listen('AppointmentCreated', (e) => {

                        this.citas.unshift(JSON.parse(e.appointment));
                         
                         audio.play()
                    
                    })
                    .listen('AppointmentDeleted', (e) => {

                    
                        var index = this.citas.indexOf(JSON.parse(e.appointment))
                            this.citas.splice(index, 1);
                    
                    })
              }
               if(this.officeId){
                  
                Echo.private(`offices.${this.officeId}.notifications`)
                    .listen('AppointmentCreatedToAssistant', (e) => {

                        this.citas.unshift(JSON.parse(e.appointment));
                         audio.play()
                    
                    })

                    .listen('AppointmentDeletedToAssistant', (e) => {

                    
                        var index = this.citas.indexOf(JSON.parse(e.appointment))
                            this.citas.splice(index, 1);
                    
                    })
              }
             
             
                
                
            }
    

       	
        },//methods
        created() {
          
          this.listen()
         
	      this.citas = this.appointments
	     
	      
	    }
    }
</script>