<template>
	

      <div class="box box-info">

          <div class="box-header with-border">
            <h3 class="box-title">Tratamiento</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
           
             
             <div class="form-group">
                <input type="text" name="search" class="form-control"  v-model="query" placeholder="Nombre..." :readonly="read">
             </div>
             <div class="form-group">
                <input type="text" name="search" class="form-control" v-model="comments" placeholder="Recomendación (Dosis)..." :readonly="read">
             </div>
              <div class="form-group">
                <button @click="hit" class="btn btn-success" v-show="!read">Agregar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader"> 
              </div>
              <ul id="diagnostics-list" class="todo-list ui-sortable" v-show="dataTreatments.length">
               
                <li v-for="item in dataTreatments">
                  <!-- todo text -->
                  <span><span class="text"> <b>{{ item.name }}:</b></span> {{ item.comments }}</span>
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
      //props: ['treatments','appointment_id'],
       props: {
        treatments: {
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
          comments : "",
          dataTreatments:[],
          loader:false


        }
          
      },
      
      methods: {
          
          hit(){
            console.log('hit');
           

            if(!this.query)
              return

            this.loader = true;
            this.add(this.query, this.comments);
            this.query = "";
            this.comments = "";
          },
          add(treatment, comments) {

            
            
            this.$http.post('/medic/treatments', {appointment_id: this.appointment_id, name: treatment, comments:comments}).then((response) => {

                  
                  console.log(response);
                  

                   if(response.status == 200 || response.status == 201)
                  {
                    this.dataTreatments.push(response.data);
                 
                    bus.$emit('actSummaryTreatments', this.dataTreatments);
                  }
                  this.loader = false;

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el Tratamiento', 'danger');
                  this.loader = false;
              });

            

          },
          remove(item){
           
            this.loader = true;
            this.$http.delete('/medic/treatments/'+item.id).then((response) => {

                 
                  console.log(response);
        

                   if(response.status == 200 || response.status == 201)
                  {
                     var index = this.dataTreatments.indexOf(item)
                    this.dataTreatments.splice(index, 1);
                
                    bus.$emit('actSummaryTreatments', this.dataTreatments);
                  }
                  this.loader = false;

              }, (response) => {
                  
                   bus.$emit('alert', 'Error al elimnar el Tratamiento', 'danger');
                  this.loader = false;
              });



          }
     
      },
      created () {
           console.log('Component ready. Tratamiento.')
          
           if(this.treatments)
           {
            
                this.dataTreatments = this.treatments;
            }
           
      }
      
    }
</script>