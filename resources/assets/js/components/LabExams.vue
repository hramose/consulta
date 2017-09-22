<template>
	
<div class="box box-danger " >

          <div class="box-header with-border">
            <h3 class="box-title">Examenes de Laboratorio</h3>

            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
                <div class="form-group" v-show="!read">
                    <div class="input-group">
                            <input type="text" class="form-control"  id="datetimepickerLabResult" v-model="date" @blur="onBlurDatetime" placeholder="Fecha" tabindex="1">

                            <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    
                </div>
                    <div class="form-group" v-show="!read">
                    
                            <input type="text" class="form-control" v-model="name" placeholder="Examen a realizar" @keyup.enter="hit" tabindex="2">

                        
                    
                    
                </div>
                
                <div class="form-group">
                            <button @click="hit" class="btn btn-success" v-show="!read" v-bind:disabled="loader">Agregar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader" tabindex="3"> 
                </div>
                <div class="box box-solid" v-for="itemDate in dataExams">
                    <div class="box-header with-border">
                        <h3 class="box-title"> {{ formatDate(itemDate.date) }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul id="medicines-list" class="todo-list ui-sortable" v-show="itemDate.exams.length">
       
                            <li v-for="item in itemDate.exams">
                            <!-- todo text -->
                            <span><span class="text"> {{ item.name }}</span></span>
                            <!-- General tools such as edit or delete-->
                            <div class="tools">
                                
                                <i class="fa fa-trash-o delete"  @click="remove(item)" v-show="!read" v-bind:disabled="loader"></i>
                            </div>
                            </li>
                        
                        </ul>
                    </div>
                </div>
                
             
                
          </div>
    </div>
      
    

  
</template>

<script>
    export default {
      //props: ['medicines','patient_id'],
       props: {
         patient_id: {
          type: Number
          
        },
         appointment_id: {
          type: Number
          
        },
        exams: {
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
          date : "",
          file:'',
          name:'',
          dataExams:[],
          loader:false


        }
          
      },
      
      methods: {
           formatDate(date){
               return moment(date).format("YYYY-MM-DD");
           },
           onBlurDatetime(e){
            const value = e.target.value;
            console.log('onInput fired', value)
            
            //Add this line
            

            this.date = value;
            this.$emit('input')
            },
          hit(){
            console.log('hit');
            if(!this.date || !this.name)
              return

            this.loader = true;
            this.add();
            
          },
          
          add() {

            let form = new FormData();
          
            form.append('date', this.date);
            form.append('name', this.name);
            form.append('appointment_id', this.appointment_id);
             
            
              this.$http.post(this.url +'/'+ this.patient_id +'/labexams', form).then((response) => {
                 
                  if(response.status == 200)
                  {
                   
                    bus.$emit('alert', 'Examen Agregado','success');
                    bus.$emit('actSummaryLabexams', response.data);
                    console.log(response.data)
                     this.loadResults()
                   
                  }
                  
                  this.name = "";
              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el Examen', 'danger');
                   this.loader = false;
              });
             
            

          },
          
          remove(item){
            
            if(this.loader) return

            this.loader = true;
            this.$http.delete(this.url +'/labexams/'+item.id, { body: { appointment_id: this.appointment_id }}).then((response) => {

                  if(response.status == 200)
                  {
                    this.loadResults();
                    bus.$emit('alert', 'Examen Eliminado','success');
                  }
                 
              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar el Examen', 'danger');
                   this.loader = false;
              });


          },
          loadResults(){
              let queryParam = {
                ['appointment_id']: this.appointment_id
              }
               
               this.$http.get(this.url +'/'+ this.patient_id +'/labexams', {params: Object.assign(queryParam, this.data)})
                .then(resp => {
                 

                 this.dataExams = resp.data
                 this.loader = false;

                
                }, (response) => {
                  
                   bus.$emit('alert', 'Error al cargar el exams', 'danger');
                   this.loader = false;
              });
          },
         

      },
      created () {
           console.log('Component ready. Lab exams.')
          
            this.loadResults();
          
            this.date = moment().format("YYYY-MM-DD");
           
      }
      
    }
</script>