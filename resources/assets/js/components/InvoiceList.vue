<template>
	<div>
		<div>
            	<h4 class="label label-success label-large">Total: ₡{{ money(total) }}</h4>
            </div>
		<div class="table-responsive">
            <table class="table no-margin">
              <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Clínica o consultorio</th>
                <th>Total</th>
                <th>Estado</th>
               
              </tr>
              </thead>
              <tbody>
              
                  <tr v-for="item in facturas">
                    <td>{{ item.consecutivo }}</td>
                   
                    <td>
                     {{ item.created_at }}
                    </td>
                      <td>{{ item.clinic.name }}</td>
                   
                    <td>{{ money(item.total) }}</span></td>
                    <td>
                       
                          <span class="label label-success" v-if="item.status == 1">Facturada</span>
                          <span class="label label-warning" v-else>Enviada a secretaria</span>
                        
                    </td>
                  
                  </tr>
            
              
              </tbody>
             
     
            </table>
            
          </div>
	</div>
</template>
<script>

 export default {
        //props: ['patients','fromModal'],
         props: {
         	invoices: Array,
         	total: Number,
		    
		},
        data () {
	        return {
	 
	          facturas:[],
	          loader:false,
	         
	          
	        }
	      },
	      
        methods: {
	        
        	 money(n, currency) {
                return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
            },

          	addToList(invoice){

          		this.facturas.unshift(invoice);
          	}//addToList

       	
        },//methods
        created() {
          
          this.facturas = this.invoices;
	     
	      bus.$on('addToInvoiceList', this.addToList);
	      
	    }
    }
</script>