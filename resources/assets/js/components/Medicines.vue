<template>
	
<div>
      <div class="form-group">
          <input type="text" name="search" class="form-control" @keydown.enter="hit" v-model="query" placeholder="Medicamentos">
       </div>
       <div class="form-group">
                <button @click="hit" class="btn btn-success" v-show="!read">Agregar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader"> 
        </div>
      <ul id="medicines-list" class="todo-list ui-sortable" v-show="dataMedicines.length">
       
        <li v-for="item in dataMedicines">
          <!-- todo text -->
          <span><span class="text"> {{ item.name }}</span></span>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            
            <i class="fa fa-trash-o delete" @click="remove(item)" v-show="!read && item.medic_id == medic_id"></i>
          </div>
        </li>
       
      </ul>
      
    
</div>
  
</template>

<script>
    export default {
      //props: ['medicines','patient_id'],
       props: {
         patient_id: {
          type: Number
          
        },
         medic_id: {
          type: Number,
          default:0
          
        },
        medicines: {
          type: Array
          
        },
        url:{
          type:String,
          default: '/medic/patients'
        },
         read:{
          type:Boolean,
          default: false
        }
    },
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
            if(!this.query || this.read || this.loader)
              return
            

            this.loader = true;
            this.add(this.query);
            this.query = "";
          },
          add(medicine) {

            
            
              this.$http.post(this.url +'/'+ this.patient_id +'/medicines', {name: medicine}).then((response) => {
    
                  if(response.status == 200)
                  {
                    this.dataMedicines.push(response.data);
                    bus.$emit('alert', 'Medicamento Agregado','success');
                    bus.$emit('actSummaryMedicines', this.dataMedicines);
                  }
                  this.loader = false;

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el medicamento', 'danger');
                  this.loader = false;
              });

            

          },
          remove(item){
            
            if(this.loader)
                return

            this.loader = true;
            this.$http.delete(this.url +'/medicines/'+item.id).then((response) => {

                  if(response.status == 200)
                  {
                     var index = this.dataMedicines.indexOf(item)
                    this.dataMedicines.splice(index, 1);
                    bus.$emit('alert', 'Medicamento Eliminado','success');
                  }
                  this.loader = false;
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