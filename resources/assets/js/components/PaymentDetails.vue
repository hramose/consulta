<template>	
 


                    

        <div class="table-responsive">
                <table class="table no-margin">
                <thead>
                <tr>
                    
                    <th>Consultas Atendidas</th>
                    <th>Monto por Citas Online</th>
                    <th>Periodo</th>
                    <th>Total</th>
                  
                    
                    
                </tr>
                </thead>
                <tbody>
                <tr>
                  
                    <td>{{ data.attented }}</td>
                    <td>${{ money(data.amountByAttended) }}</td>
                    <td>{{ data.month }}</td>
                    <td>${{ money(parseFloat(data.attented_amount)) }}</td>
                  
                    
                </tr>
             
                
                </tbody>
                </table>
        </div>
  
                        
                  

                
          
         
          

</template>

<script>
 

    export default {
      props: ['income_id'],
      data () {
        return {
        

          data:{
              attented:0,
              amountByAttended: 0,
              attented_amount: 0,
              month: '',
             
          },
          loader: false,
         
          
        }
      },
     

       methods: {
         money(n, currency) {
                
                if(n)
                    return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                
                return 0;
            },
         
         
         /*totalIncomes(incomes){
              let total = 0;

              for (var i = 0; i < incomes.length; i++) {
                total += parseFloat(incomes[i].amount);
              }
              
              return total;
            
          
        },*/
       

        getPaymentDetail(){
         
          
            this.data = [];
           
            
             

                 this.loader = true;

                this.$http.get('/medic/payments/'+ this.income_id +'/details')
                .then(resp => {
                  
                   console.log(resp.data);
                   this.data = resp.data
                  
                   this.loader = false;
                });



             



        }

      },
      created () {
            this.getPaymentDetail();

            console.log('Component ready. reports Clinic')

            
            
        }
    }
</script>