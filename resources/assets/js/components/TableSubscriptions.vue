<template>
<div>
 
          <table class="table table-bordered">
                <tbody>
                <tr>
         
                  <th class="text-center">Subscripción</th>
                  <th class="text-center">Duración</th>
                  <th class="text-center">Acción</th>
                 
                </tr>
               
                    
                    <tr v-for="subscription in subscriptionsPackages">
                      
                      <td>{{ subscription.title }}</td>
                      <td>{{ subscription.quantity }} Meses</td>
                      <td>
                       <!-- <form method="POST" v-bind:action="getUrl(subscription)" class="form-horizontal">
                          <input type="hidden" name="_token" :value="token">

                          <button type="submit" class="btn btn-success">Seleccionar</button>
                         
                      </form> -->
                      <a :href="getUrl(subscription)" class="btn btn-success">Seleccionar</a>
                       
                      </td>
                      
                    </tr>
              
                
               
              </tbody>
            </table>
               
            
    
</div>
</template>

<script>


    
    export default {
      
      props:['token','change'],
    
      data () {
        return {
          loader:false,
          subscriptionsPackages:[]
        }
      },
  
      methods: {
           getUrl(subscription){
            
          return (this.change && this.change == 1) ? '/medic/subscriptions/'+ subscription.id +'/change' :'/medic/subscriptions/'+ subscription.id +'/buy';

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