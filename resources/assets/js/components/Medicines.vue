<template>
	
<div>
  
      <input type="text" name="search" class="form-control" @keydown.enter="hit" v-model="query" placeholder="Medicamentos">
      <ul id="medicines-list" class="todo-list ui-sortable" v-show="dataMedicines.length">
       
        <li v-for="item in dataMedicines">
          <!-- todo text -->
          <span><span class="text"> {{ item.name }}</span></span>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            
            <i class="fa fa-trash-o delete" @click="remove(item)"></i>
          </div>
        </li>
       
      </ul>
      
    
</div>
  
</template>

<script>
    export default {
      props: ['medicines','patient_id'],
      data () {
        return {
          query : "",
          dataMedicines:[],
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
          add(medicine) {

            
            
              this.$http.post('/patients/'+this.patient_id +'/medicines', {patient_id: this.patient_id, name: medicine}).then((response) => {
    
                  if(response.status == 200)
                  {
                    this.dataMedicines.push(response.data);
                    bus.$emit('alert', 'Medicamento Agregado','success');
                    bus.$emit('actSummaryMedicines', this.dataMedicines);
                  }

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el medicamento', 'danger');
                  this.loader = false;
              });

            

          },
          remove(item){
           

            this.$http.delete('/patients/medicines/'+item.id).then((response) => {

                  if(response.status == 200)
                  {
                     var index = this.dataMedicines.indexOf(item)
                    this.dataMedicines.splice(index, 1);
                    bus.$emit('alert', 'Medicamento Eliminado','success');
                  }

              }, (response) => {
                  
                   bus.$emit('alert', 'Error al guardar el medicamento', 'danger');
                  this.loader = false;
              });


          }
     
      },
      created () {
           console.log('Component ready. Medicines.')
          
           if(this.medicines.length)
           {
            
                this.dataMedicines = this.medicines;
            }
           
      }
      
    }
</script>