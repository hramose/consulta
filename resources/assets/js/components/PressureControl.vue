<template>
	
<div class="content">
      <div class="row">
       
          <div class="col-sm-6">
             <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control"  id="datetimepicker1" v-model="date_control" @blur="onBlurDate">

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
                  <input type="text" class="form-control"  id="datetimepicker2" v-model="time_control" @blur="onBlurHour">

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
     
        <div class="col-sm-6">
           <div class="form-group">
            <input type="text" name="ps" class="form-control"  v-model="ps" placeholder="P.S" >
            <form-error v-if="errors.ps" :errors="errors" style="float:right;">
                    {{ errors.ps[0] }}
                </form-error>
          </div>
        </div>
      
        <div class="col-sm-6">
          <div class="form-group">
            <input type="text" name="pd" class="form-control" v-model="pd" placeholder="P.D" >
            <form-error v-if="errors.pd" :errors="errors" style="float:right;">
                    {{ errors.pd[0] }}
                </form-error>
         </div>
          
        </div>
      
        
      </div> 
      <div class="row">
     
        <div class="form-group">
          <button @click="hit" class="btn btn-success" :disabled="dataPressures.length >= this.limit">Agregar</button>
          <span class="label label-warning" v-show="dataPressures.length >= this.limit">Haz alcanzado el limite de {{ limit}} registros</span> 
        </div>
      </div>
      <ul id="medicines-list" class="todo-list ui-sortable" v-show="dataPressures.length">
       
        <li v-for="item in dataPressures">
          <!-- todo text -->
          <span><span class="text"> P.S: {{ item.ps }} / P.D: {{ item.pd }} - {{ item.date_control }} {{ item.time_control }}</span></span>
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
        pressures: {
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
          ps : "",
          pd : "",
          date_control : new Date().toLocaleDateString(),
          time_control : new Date().toLocaleTimeString(),
          dataPressures:[],
          loader:false,
          errors: [],
          limit: 10


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

           /* if(!this.ps)
              return*/

            this.add(this.ps, this.pd, this.date_control, this.time_control);
            this.ps = "";
            this.pd = "";
           
          },
          add(ps, pd, date, time) {

            
            
              this.$http.post(this.url +'/'+ this.patient_id +'/pressures', {ps: ps, pd:pd, date_control:date, time_control:time}).then((response) => {
    
                  if(response.status == 200)
                  {
                    this.dataPressures.push(response.data);
                    bus.$emit('alert', 'Control de Presion Agregado','success');
                    this.errors = [];
                  }

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar Control de Presion', 'danger');
                  this.loader = false;
                  this.errors = response.data;
              });

            

          },
          remove(item){
           

            this.$http.delete(this.url +'/pressures/'+item.id).then((response) => {

                  if(response.status == 200)
                  {
                     var index = this.dataPressures.indexOf(item)
                    this.dataPressures.splice(index, 1);
                    bus.$emit('alert', 'Control de Presion Eliminado','success');
                  }

              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar Control de Presion', 'danger');
                  this.loader = false;
              });


          }
     
      },
      created () {
           console.log('Component ready. pressures.')
          
           if(this.pressures.length)
           {
            
                this.dataPressures = this.pressures;
            }
           
      }
      
    }
</script>