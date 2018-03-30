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
          <button @click="hit" class="btn btn-success">Agregar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
          
        </div>
      </div>
      <ul id="medicines-list" class="todo-list ui-sortable" v-show="items.length">
       
        <li v-for="item in items">
          <!-- todo text -->
          <span> P.S: <span class="text">{{ item.ps }}</span> / P.D: <span class="text">{{ item.pd }}</span> <span class="date pull-right"> {{ item.date_control }} {{ item.time_control }}</span></span>
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
      props: ['patient','pressures','today','time'],
      
     components:{ FormError },
      data () {
        return {
          ps : "",
          pd : "",
          date_control : this.today,
          time_control : this.time,
          items:this.pressures,
          loader:false,
          errors: [],
         


        }
          
      },
      
      methods: {
         formatDate(date){
            return  new Date(date).toLocaleDateString();
         },
          onBlurDate(e){
          const value = e.target.value;
          console.log('onInput fired', value)

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
         
            this.add(this.ps, this.pd, this.date_control, this.time_control);
            this.ps = "";
            this.pd = "";
           
          },
          add(ps, pd, date, time) {

              
            
              this.$http.post(`/pharmacy/patients/${this.patient.id}/pressures`, {ps: ps, pd:pd, date_control:date, time_control:time}).then((response) => {
    
                   if(response.status == 200 || response.status == 201)
                  {
                    this.items.unshift(response.data);
                    bus.$emit('alert', 'Control de Presion Agregado','success');
                    this.errors = [];
                  }
                  this.loader = false;

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar Control de Presion', 'danger');
                  this.loader = false;
                  this.errors = response.data;
              });

            

          },
          remove(item){
           
            this.loader = true;
            this.$http.delete(`/pharmacy/patients/${this.patient.id}/pressures/${item.id}`).then((response) => {

                   if(response.status == 200 || response.status == 201)
                  {
                     var index = this.items.indexOf(item)
                    this.items.splice(index, 1);
                    bus.$emit('alert', 'Control de Presion Eliminado','success');
                  }
                  this.loader = false;
              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar Control de Presion', 'danger');
                  this.loader = false;
              });


          }
     
      }
    
      
    }
</script>