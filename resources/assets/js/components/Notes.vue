<template>
	
      <div class="box box-success collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title">Notas</h3>

              <div class="box-tools pull-right">
                <span data-toggle="tooltip" title="" class="badge bg-red" :data-original-title="dataNotes.length +' Nota(s)'" v-show="dataNotes.length > 0">{{ dataNotes.length }}</span>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: none;">
               <ul id="diagnostics-list" class="todo-list ui-sortable" v-show="dataNotes.length">
               
                <li v-for="item in dataNotes">
                  <!-- todo text -->
                  <span><span class="text"> <b>{{ item.description }}</b></span> - {{ item.created_at }}</span>
                  <!-- General tools such as edit or delete-->
                  
                </li>
               
               </ul>
               <div class="form-group" v-show="!read">
                  <textarea name="description" cols="30" rows="4" v-model="description" class="form-control"></textarea>
                
              </div>
              
                <div class="form-group" v-show="!read">
                  <button @click="hit" class="btn btn-success">Agregar Nota</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader"> 
                </div>
            </div>
            <!-- /.box-body -->
      </div>
     
            
      

  
</template>

<script>
    export default {
      //props: ['notes','appointment_id'],
       props: {
        notes: {
          type: Array
        },
        appointment_id: {
          type: Number
        },
        read:{
          type:Boolean,
          default: false
        }
      },
      data () {
        return {
        
          description : "",
          dataNotes:[],
          loader:false


        }
          
      },
      
      methods: {
          
          hit(){
            console.log('hit');
           

            if(!this.description)
              return

            this.loader = true;
            this.add(this.description);
            this.description = "";
           
          },
          add(description) {

            
            
            this.$http.post('/medic/notes', {appointment_id: this.appointment_id, description: description}).then((response) => {

                  
                  console.log(response);
                  

                  if(response.status == 200)
                  {
                    this.dataNotes.push(response.data);
                 
                    
                  }
                  this.loader = false;

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el Nota', 'danger');
                  this.loader = false;
              });

            

          },
         
      },
      created () {
           console.log('Component ready. Notas.')
          
           if(this.notes)
           {
            
                this.dataNotes = this.notes;
            }
           
      }
      
    }
</script>