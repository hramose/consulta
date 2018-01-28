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
                        <!-- <form method="POST" v-bind:action="getUrl(payment)" class="form-horizontal">
                          <input type="hidden" name="_token" :value="token">
               
                        <button type="submit" class="btn btn-success btn-sm">Pagar</button>
                      </form> -->
                      <a :href="getUrl(payment)" class="btn btn-success">{{ (payment.type == 'MS') ? 'Renovar' : 'Pagar' }}</a>
                      <a href="#" data-toggle="modal" data-target="#modalSubscription" class="btn btn-success" v-if="payment.type == 'MS'">Cambiar Plan</a>
                      </td>
                      
                    </tr>
                
                <tr v-if="monthlyCharges.lenght > 1">
                  
                  <td colspan="3"> <a href="/medic/payments/create" class="btn btn-success">Pagar Todo</a></td>
                 
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
          
          return '/medic/payments/'+ payment.id +'/create';

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