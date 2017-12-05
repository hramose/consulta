<template>
<div>
 
          <table class="table table-bordered">
                <tbody>
                <tr>
         
                  <th class="text-center">Monto</th>
                  <th class="text-center">Periodo</th>
                  <th class="text-center">Acción</th>
                 
                </tr>
               
                    
                    <tr v-for="payment in monthlyCharges">
                      
                      <td>${{ money(payment.amount) }}</td>
                      <td>
                        {{ (payment.type == 'MS') ? payment.period_from +' -- '+ payment.period_to : payment.month + '-' + payment.year }}</td>
                      <td>
                        <form method="POST" v-bind:action="getUrl(payment)" class="form-horizontal">
                          <input type="hidden" name="_token" :value="token">
               
                        <button type="submit" class="btn btn-success btn-sm">Pagar</button>
                      </form>
                      </td>
                      
                    </tr>
              
                
               
              </tbody>
            </table>
               
            
    
</div>
</template>

<script>


    
    export default {
      
      props:['monthlyCharges','token'],
    
      data () {
        return {
          loader:false,

        }
      },
  
      methods: {
         getUrl(payment){
          
          return '/medic/payments/'+ payment.id +'/pay';

        },
        money(n, currency) {
                return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
            },

      }, //methods

      created () {
             console.log('Component ready. Modal Appointments')

            
        }
    }
</script>