<template>
	

      <div class="box box-danger">

          <div class="box-header with-border">
            <h3 class="box-title">Diagnosticos</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
           
             
             <input type="text" name="search" class="form-control" @keydown.enter="hit" v-model="query" placeholder="Nombre..." :readonly="read">
              <ul id="diagnostics-list" class="todo-list ui-sortable" v-show="dataDiagnostics.length">
               
                <li v-for="item in dataDiagnostics">
                  <!-- todo text -->
                  <span><span class="text"> {{ item.name }}</span></span>
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    
                    <i class="fa fa-trash-o delete" @click="remove(item)" v-show="!read"></i>
                  </div>
                </li>
               
              </ul>
            
              
          </div>
          <!-- /.box-body -->
      </div>
            
      

  
</template>

<script>
    export default {
      //props: ['diagnostics','appointment_id'],
      props: {
        diagnostics: {
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
          query : "",
          dataDiagnostics:[],
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
          add(diagnostic) {

            
            
            this.$http.post('/medic/diagnostics', {appointment_id: this.appointment_id, name: diagnostic}).then((response) => {

                  
                  console.log(response);
                  

                  if(response.status == 200)
                  {
                    this.dataDiagnostics.push(response.data);
                    bus.$emit('actHistoryDiagnostics', response.data);
                    bus.$emit('actSummaryDiagnostics', this.dataDiagnostics);
                  }

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el diagnostico', 'danger');
                  this.loader = false;
              });

            

          },
          remove(item){
           

            this.$http.delete('/medic/diagnostics/'+item.id).then((response) => {

                 
                  console.log(response);
        

                  if(response.status == 200)
                  {
                     var index = this.dataDiagnostics.indexOf(item)
                    this.dataDiagnostics.splice(index, 1);
                
                    bus.$emit('actSummaryDiagnostics', this.dataDiagnostics);
                  }

              }, (response) => {
                  
                   bus.$emit('alert', 'Error al elimnar el diagnostico', 'danger');
                  this.loader = false;
              });



          }
     
      },
      created () {
           console.log('Component ready. diagnostico.')
          
           if(this.diagnostics)
           {
            
                this.dataDiagnostics = this.diagnostics;
            }
           
      }
      
    }
</script>