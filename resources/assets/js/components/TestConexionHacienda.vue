<template>
	
    <div>
        <button @click="testConexion()" class="btn btn-success">Probar Conexion Hacienda</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
        <div>
            <h3>{{ result }}</h3>
        </div>
    </div>

    
            
      

  
</template>

<script>
    export default {
      props:['userId'],
      data () {
        return {
          result : "",
          loader:false


        }
          
      },
      
      methods: {
          
         
          testConexion() {

            this.loader = true;
            
            this.$http.post('/users/'+ this.userId +'/fe/conexion').then((response) => {

                  
                  console.log(response);
                  

                  if(response.status == 200)
                  {
                    if(response.data.access_token)
                        this.result = 'Conexion Exitosa'
                    else
                        this.result = 'Ha ocurrido un error en la Conexion con hacienda 1';
                    
                  }
                  this.loader = false;

              }, (response) => {
                 
                   console.error(response);
                   this.result = 'Ha ocurrido un error en la Conexion con hacienda 2';
                  this.loader = false;
              });

            

          }
        
     
      },
      created () {
           console.log('Component ready. test conexion hacienda.')
          
          
           
      }
      
    }
</script>