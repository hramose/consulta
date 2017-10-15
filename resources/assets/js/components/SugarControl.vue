<template>
	
<div class="content">
      <div class="row">
       
          <div class="col-sm-6">
             <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control"  id="datetimepicker3" v-model="date_control" @blur="onBlurDate">

                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
                 <form-error v-if="errors.date_control" :errors="errors" style="float:right;">
                    {{ errors.date_control[0] }}
                </form-error>
              </div>
          </div>
        
       
          <div class="col-sm-6">
             <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control"  id="datetimepicker4" v-model="time_control" @blur="onBlurHour">

                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                </div>
                <form-error v-if="errors.time_control" :errors="errors" style="float:right;">
                    {{ errors.time_control[0] }}
                </form-error>
              </div>
            
          </div>
        
        
      </div> 

      <div class="row">
     
        <div class="col-sm-12">
           <div class="form-group">
            <input type="text" name="glicemia" class="form-control"  v-model="glicemia" placeholder="Glicemia" >
            <form-error v-if="errors.glicemia" :errors="errors" style="float:right;">
                    {{ errors.glicemia[0] }}
                </form-error>
          </div>
        </div>
        
      </div> 
      <div class="row">
     
        <div class="form-group">
          <button @click="hit" class="btn btn-success" :disabled="dataSugars.length >= this.limit">Agregar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
          <span class="label label-warning" v-show="dataSugars.length >= this.limit">Haz alcanzado el limite de {{ limit}} registros</span> 
        </div>
      </div>
      <ul id="medicines-list" class="todo-list ui-sortable" v-show="dataSugars.length">
       
        <li v-for="item in dataSugars">
          <!-- todo text -->
          <span> Glicemia: <span class="text">{{ item.glicemia }}</span> <span class="date pull-right"> {{ item.date_control }} {{ item.time_control }}</span></span>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            
            <i class="fa fa-trash-o delete" @click="remove(item)"></i>
          </div>
        </li>
       
      </ul>
      
    
</div>
  
</template>

<script>
    import FormError from './FormError.vue';
    export default {
      //props: ['allergies','patient_id'],
       props: {
         patient_id: {
          type: Number
          
        },
         today: {
          type: String
          
        },
         time: {
          type: String
          
        },
        sugars: {
          type: Array
          
        },
        url:{
          type:String,
          default: '/account/patients'
        }
    },
     components:{
          FormError
         
         
        },
      data () {
        return {
          glicemia : "",
          date_control : '',
          time_control : '',
          dataSugars:[],
          loader:false,
          errors: [],
          limit:10


        }
          
      },
      
      methods: {
         formatDate(date){
            return  new Date(date).toLocaleDateString();
         },
          onBlurDate(e){
          const value = e.target.value;
          console.log('onInput fired', value)
          
          //Add this line
          

          this.date_control = value;
          this.$emit('input')
        },
         onBlurHour(e){
          const value = e.target.value;
          console.log('onInput fired', value)
          
          //Add this line

          this.time_control = value;
          this.$emit('input')
        },
          hit(){
            console.log('hit');
            this.loader = true;
           /* if(!this.ps)
              return*/

            this.add(this.glicemia, this.date_control, this.time_control);
            this.glicemia = "";
           
           
          },
          add(glicemia, date, time) {

            
            
              this.$http.post(this.url +'/'+ this.patient_id +'/sugars', {glicemia: glicemia, date_control:date, time_control:time}).then((response) => {
    
                  if(response.status == 200)
                  {
                    this.dataSugars.unshift(response.data);
                    bus.$emit('alert', 'Control de Azúcar Agregado','success');
                    this.errors = [];
                  }
                  this.loader = false;

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar Control de Azúcar', 'danger');
                  this.loader = false;
                  this.errors = response.data;
              });

            

          },
          remove(item){
           
            this.loader = true;
            this.$http.delete(this.url +'/sugars/'+item.id).then((response) => {

                  if(response.status == 200)
                  {
                     var index = this.dataSugars.indexOf(item)
                    this.dataSugars.splice(index, 1);
                    bus.$emit('alert', 'Control de Azúcar Eliminado','success');
                  }
                  this.loader = false;

              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar Control de Azúcar', 'danger');
                  this.loader = false;
              });


          }
     
      },
      created () {
           console.log('Component ready. sugars.')
          
           if(this.sugars.length)
           {
            
                this.dataSugars = this.sugars;
            }
            if(this.today)
           {
            
                this.date_control = this.today;
            }
            if(this.time)
           {
            
                this.time_control = this.time;
            }
           
      }
      
    }
</script>