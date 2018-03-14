<template>
	<li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" v-show="total()"> 
                            {{ total() }} 
                  
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Tienes {{ total() }} nuevo(s) solicitud(es) de médico(s)</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  
                  <li v-for="medic in medicos" class="appointment-li">
                    <a href="#">
                        <h5><span @click="toggleDetails(medic)" style="padding: 2rem 0;"> {{ medic.name  }}</span> <span @click="viewed(medic)" v-show="!read" class="pull-right"> <input type="checkbox" name="viewed"  />   </span>   </h5>
                      
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
                        
                        <img class="profile-user-img img-responsive img-circle" v-bind:src="medicSelected.photo" alt="User profile picture">  
                        
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">
                            {{ medicSelected.name }} </h3>
                        <h5 class="widget-user-desc"></h5>
                    </div>
                    <div class="box-footer no-padding">
                        
                        
                        
                        
                            <ul class="nav nav-stacked">
                                
                                <li><a href="#"><div style="color:#444;">Teléfono: {{ medicSelected.phone }} </div> </a></li>
                                <li><a href="#"><div style="color:#444;">Email: {{ medicSelected.email }} </div></a></li>
                                
                             
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
             medics: Array,
             url:{
                type:String,
                default: '/clinic/appointments'
            },
             read:{
                type:Boolean,
                default: false
            },
            
		    
		},
        data () {
	        return {
	 
	          medicos:[],
	          loader:false,
              medicSelected:{},
              showDetails:false
             
	          
	        }
	      },
	      
        methods: {
            formatDate(date){
               return moment(date).format("YYYY-MM-DD HH:mm");
           },
            total(){
                return this.medicos.length;
            },
            toggleDetails(item){
               
                if(item){
              
                    if(this.medicSelected == item)
                        this.showDetails = !this.showDetails
                    else{
                        this.showDetails = true;
                    }
                    this.medicSelected = item
                }

                
            },
	        viewed(item){
               

               this.$http.post('/clinic/medics/'+ item.id +'/assign', {}).then((response) => {
		                    console.log(response.status);
		                    console.log(response.data);
		                    if((response.status == 200 || response.status == 201) && response.data)
		                    {
		                       window.location.href = "/clinic/appointments";
		                    }
		                   this.loader = false;
		              }, (response) => {
                    
		                  console.log('error al asisgnar medico')
		                  this.loader = false;
		                   this.errors = response.data.errors;
		              });

               /* var resource = this.$resource(this.url +'/'+ item.id +'/viewed');

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
                   
                });*/

               
            },
            listen(){
              var audio = new Audio('/img/notification.mp3');
           
              if(this.userId){

                Echo.private(`adminclinic.${this.userId}.notifications`)
                    .listen('MedicRequest', (e) => {
                        
                        this.medicos.push(JSON.parse(e.medic));
                         
                         audio.play()
                    
                    })
                    
              }
              
             
             
                
                
            }
    

       	
        },//methods
        created() {
          
          this.listen()
         
	      this.medicos = this.medics
	     
	      
	    }
    }
</script>