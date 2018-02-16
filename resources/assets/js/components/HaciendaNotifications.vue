<template>
    <li class="dropdown messages-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-envelope-o"></i>
            <span class="label label-success">4</span>
        </a>
        <ul class="dropdown-menu">
             <li class="header">Tienes {{ total() }} mensajes de hacienda</li>
            <li>
            <!-- inner menu: contains the actual data -->
            
            <ul class="menu">
                <li v-for="mensaje in mensajes" class="appointment-li">
                    <a href="#">
                         <span @click="viewed(mensaje)" v-show="!read" class="pull-left"> <input type="checkbox" name="viewed"  />   </span>
                         <h4><span @click="toggleDetails(mensaje)" style="padding: 2rem 0;"> {{ mensaje.title }}</span>  <small><i class="fa fa-clock-o"></i> {{ formatDate(mensaje.created_at) }} </small>    </h4>
                       <p>{{ mensaje.body }}</p>
                    </a>
                    
                </li>
                
            </ul>
            </li>
            <li class="footer"></li>
            <div v-show="showDetails" class="preview-notification-details">

                  <div class="box box-widget widget-user-2">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    
                    <div class="bg-teal widget-user-header " >
                       
                        <div class="widget-user-image">
                        
                        <!-- <img class="profile-user-img img-responsive img-circle" v-bind:src="mensajeSelected.patient.photo" alt="User profile picture">   -->
                        
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username">
                            {{ mensajeSelected.title }} </h3>
                        <h5 class="widget-user-desc">{{ mensajeSelected.body }}</h5>
                    </div>
                    <div class="box-footer no-padding">
                        
                        
                        
                        
                            
                        
                        
                        
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
             messagesHacienda: Array,
             url:{
                type:String,
                default: '/hacienda/messages'
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
	 
	          mensajes:[],
	          loader:false,
              mensajeSelected:{
                  user:{},
                  patient:{}
              },
              showDetails:false
             
	          
	        }
	      },
	      
        methods: {
            formatDate(date){
               return moment(date).fromNow();
           },
            total(){
                return this.mensajes.length;
            },
            toggleDetails(item){
               
                //if(item.patient){
              
                    if(this.mensajeSelected == item)
                        this.showDetails = !this.showDetails
                    else{
                        this.showDetails = true;
                    }
                    this.mensajeSelected = item
               // }

                
            },
	        viewed(item){
               

                var resource = this.$resource(this.url +'/'+ item.id +'/viewed');

                resource.update().then((response) => {
                    
                     
                       this.loader = false;
                       var index = this.mensajes.indexOf(item)
                        this.mensajes.splice(index, 1);
                        this.showDetails = false;
                        this.mensajeSelected = {
                            
                        };
                     
                }, (response) => {
                    console.log(response.data)
                    this.loader = false;
                   
                });

               
            },
            listen(){
              var audio = new Audio('/img/notification.mp3');
           
              if(this.userId){

                Echo.private(`users.${this.userId}.hacienda`)
                    .listen('HaciendaResponse', (e) => {

                        console.log(e)
                        this.mensajes.push(JSON.parse(e.resp));
                         audio.play()
                    
                    })
                    
              }
               if(this.officeId){
                  
                Echo.private(`offices.${this.officeId}.hacienda`)
                    .listen('HaciendaResponseToAssistant', (e) => {

                        this.mensajes.push(JSON.parse(e.resp));
                         audio.play()
                    
                    })

                    
              }
             
             
                
                
            }
    

       	
        },//methods
        created() {
          
          this.listen()
          let tempData = [
              {
                  title:'Factura Rechazada',
                  body:'La Factura fue rechaza por que el consecutivo ya lo tenemos en nuestra base de datos',
                  created_at:'2018-02-07 22:25:00'
              },
               {
                  title:'Factura Aprobada',
                  body:'',
                  created_at:'2018-02-07 22:25:00'
              }
          ]
	      this.mensajes = tempData
	     
	      
	    }
    }
</script>