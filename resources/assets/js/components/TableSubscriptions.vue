<template>
<div>
 
          <table class="table table-bordered">
                <tbody>
                <tr>
         
                  <th class="text-center">Subscripción</th>
                  <th class="text-center">Duración</th>
                  <th class="text-center">Acción</th>
                 
                </tr>

                    <tr>
                      <td colspan="3"><label for="fe">¿Desear utilizar Factura Electronica? </label><br>
                      <div class="form-group radios-fe">
                        <div class="radio">
                          <label>
                            <input type="radio" name="fe" id="fe-no" value="0" v-model="fe">
                            No
                          </label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio" name="fe" id="fe-si" value="1" v-model="fe">
                            Si
                          </label>
                        </div>
                        
                      </div>
                      </td>
                    </tr>
                    
                    <tr v-for="subscription in subscriptionsPackages">
                      
                      <td>{{ subscription.title }}</td>
                      <td>{{ subscription.quantity }} Meses</td>
                     
                      <td>
                       <!-- <form method="POST" v-bind:action="getUrl(subscription)" class="form-horizontal">
                          <input type="hidden" name="_token" :value="token">

                          <button type="submit" class="btn btn-success">Seleccionar</button>
                         
                      </form> -->
                      <a :href="(currentPlan == subscription.id) ? '#' : getUrl(subscription)" class="btn btn-success" :disabled="currentPlan == subscription.id">Seleccionar</a>
                       
                      </td>
                       
                      
                    </tr>
                   
              
                
               
              </tbody>
            </table>
               
            
    
</div>
</template>

<script>


    
    export default {
      
      props:['token','change','currentPlan'],
    
      data () {
        return {
          loader:false,
          subscriptionsPackages:[],
          fe:0
        }
      },
  
      methods: {
           getUrl(subscription){
            
          return (this.change && this.change == 1) ? '/medic/subscriptions/'+ subscription.id +'/change?fe='+ this.fe :'/medic/subscriptions/'+ subscription.id +'/buy?fe='+ this.fe;

        },

         getSubscriptionsPackages() {
         
            
           
          
            this.$http.get('/medic/subscriptions/list')
            .then(resp => {
               
               this.subscriptionsPackages = resp.data
              
            })
            
          },

      }, //methods

      created () {

             console.log('Component ready. Modal Appointments')

         this.getSubscriptionsPackages();    
        }
    }
</script>