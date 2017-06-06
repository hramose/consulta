<template>
	
<div>
  
      <input type="text" name="search" class="form-control" @keydown.enter="hit" v-model="query" placeholder="Alergias">
      <ul id="medicines-list" class="todo-list ui-sortable" v-show="dataAllergies.length">
       
        <li v-for="item in dataAllergies">
          <!-- todo text -->
          <span><span class="text"> {{ item.name }}</span></span>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            
            <i class="fa fa-trash-o delete" @click="remove(item)" v-show="item.user.roles[0].name == 'paciente'"></i>
          </div>
        </li>
       
      </ul>
      
    
</div>
  
</template>

<script>
    export default {
      //props: ['allergies','patient_id'],
       props: {
         patient_id: {
          type: Number
          
        },
        allergies: {
          type: Array
          
        },
        url:{
          type:String,
          default: '/account/patients'
        }
    },
      data () {
        return {
          query : "",
          dataAllergies:[],
          loader:false


        }
          
      },
      
      methods: {
          
          hit(){
            console.log('hit');

            if(!this.query)
              return

            this.add(this.query);
            this.query = "";
          },
          add(allergy) {

            
            
              this.$http.post(this.url +'/'+ this.patient_id +'/allergies', {name: allergy}).then((response) => {
    
                  if(response.status == 200)
                  {
                    this.dataAllergies.push(response.data);
                    bus.$emit('alert', 'Alergia Agregado','success');
                    
                  }

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar la alergia', 'danger');
                  this.loader = false;
              });

            

          },
          remove(item){
           

            this.$http.delete(this.url +'/allergies/'+item.id).then((response) => {

                  if(response.status == 200)
                  {
                     var index = this.dataAllergies.indexOf(item)
                    this.dataAllergies.splice(index, 1);
                    bus.$emit('alert', 'Alergia Eliminada','success');
                  }

              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar la alergia', 'danger');
                  this.loader = false;
              });


          }
     
      },
      created () {
           console.log('Component ready. Allergies.')
          
           if(this.allergies.length)
           {
            
                this.dataAllergies = this.allergies;
            }
           
      }
      
    }
</script>