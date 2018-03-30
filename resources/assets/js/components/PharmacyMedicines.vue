<template>
	
<div>
       <div class="row">
     
        <div class="col-sm-6">
           <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control"  id="datetimepicker5" v-model="date_purchase" @blur="onBlurDate">

                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
                 <form-error v-if="errors.date_purchase" :errors="errors" style="float:right;">
                    {{ errors.date_purchase[0] }}
                </form-error>
              </div>
        </div>
      
        <div class="col-sm-6">
          <div class="form-group">
            <input type="text" name="name" class="form-control" v-model="name" placeholder="Medicamento" >
            <form-error v-if="errors.name" :errors="errors" style="float:right;">
                    {{ errors.name[0] }}
                </form-error>
         </div>
          
        </div>
      
        
      </div> 
       <div class="form-group">
                <button @click="hit" class="btn btn-success">Agregar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader"> 
        </div>

         <table class="table table-bordered">
                <tbody>
                <tr>
         
                  <th class="">Medicamento</th>
                  <th class="">Fecha de compra</th>
                  <th class="">Acción</th>
                 
                </tr>

                    <tr v-for="item in items">
                      
                      <td>{{ item.name }}</td>
                      <td>
                        {{ formatDate(item.date_purchase) }}
                      </td>
                      <td>
                       
                        <i class="fa fa-trash-o delete" @click="remove(item)"></i>
                      </td>
                      
                    </tr>
                
                
                
               
              </tbody>
            </table>
    
    
</div>
  
</template>

<script>
    import moment from 'moment'
    export default {
      props: ['medicines','patient','today'],
       
      data () {
        return {
          name : "",
          date_purchase : this.today,
          items:this.medicines,
          loader:false,
          errors:[]


        }
          
      },
      
      methods: {
          formatDate(date){
            return moment(date).format('YYYY-MM-DD');//new Date(date).toLocaleDateString();
         },
          onBlurDate(e){
          const value = e.target.value;
          console.log('onInput fired', value)

          this.date_purchase = value;
          this.$emit('input')
        },
          
          hit(){
            console.log('hit');
            if(!this.name || !this.date_purchase || this.loader)
              return
            

            this.loader = true;
            this.add();
            this.query = "";
          },
          add() {

            
            
              this.$http.post(`/pharmacy/patients/${this.patient.id}/medicines`, {name: this.name, date_purchase: this.date_purchase}).then((response) => {
    
                   if(response.status == 200 || response.status == 201)
                  {
                    this.items.unshift(response.data);
                    bus.$emit('alert', 'Medicamento Agregado','success');
                  
                  }
                  this.loader = false;

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el medicamento', 'danger');
                  this.loader = false;
              });

            

          },
          remove(item){
            
            if(this.loader)
                return

            this.loader = true;
            this.$http.delete(`/pharmacy/patients/${this.patient.id}/medicines/${item.id}`).then((response) => {

                   if(response.status == 200 || response.status == 201)
                  {
                     var index = this.items.indexOf(item)
                    this.items.splice(index, 1);
                    bus.$emit('alert', 'Medicamento Eliminado','success');
                  }
                  this.loader = false;
              }, (response) => {
                  
                   bus.$emit('alert', 'Error al guardar el medicamento', 'danger');
                  this.loader = false;
              });


          }
     
      }
  
      
    }
</script>