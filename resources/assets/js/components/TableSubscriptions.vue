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
                        <a href="#" class="btn btn-success">Seleccionar</a>
                      </td>
                      
                    </tr>
              
                
               
              </tbody>
            </table>
               
            
    
</div>
</template>

<script>


    
    export default {
      
      //props:[],
    
      data () {
        return {
          loader:false,
          subscriptionsPackages:[]
        }
      },
  
      methods: {

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