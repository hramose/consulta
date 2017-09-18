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

                              <div class="box-group" id="accordion">
                                    <div class="panel box box-info" v-for="exam in itemDate.exams">
                                        <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion" :href="'#exam-'+ exam.id" aria-expanded="false" class="collapsed">
                                                {{ exam.name }}
                                            </a>
                                        </h4>
                                        <div class="box-tools pull-right">
                        
                                            <i class="fa fa-trash-o delete" @click="remove(exam)" v-show="!read" v-bind:disabled="loader"></i>
                                        </div>
                                        </div>
                                        <div :id="'exam-'+ exam.id" class="panel-collapse collapse" aria-expanded="false">
                                            <div class="box-body">
                                                
                                                <div class="content">

                                                        <div class="col-sm-12 col-md-6"  v-show="!read">
                        
                                                        <div class="form-group" v-show="!read">
                                                            <photo-upload @input="handleFileUpload" :value="value" message="Subir resultado"></photo-upload>
                                                            <button @click="uploadResult(exam)" class="btn btn-info" v-show="!read" v-bind:disabled="loader">Subir</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader"> 
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12" :class="(!read) ? 'col-md-6' : ''">
                                                        <b>Resultados:</b>
                                                        <ul id="medicines-list" class="todo-list ui-sortable" v-show="exam.results.length">
                                            
                                                            <li v-for="item in exam.results"> <a v-bind:href="'/storage/patients/'+ patient_id +'/labresults/'+ item.id +'/'+ item.name " target="_blank">{{ item.name}}</a>  
                                                                <div class="tools">
                                                                    
                                                                    <i class="fa fa-trash-o delete" @click="removeResult(item)" v-show="!read" v-bind:disabled="loader"></i>
                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                                                
                                                </div>
                                            
                                                
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        
                    </div>
                    <!-- /.box-body -->
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
          dataResults:[],
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
          uploadResult(item){

            if(!this.file)
               return 
              
              const config = {
                headers: {
                'content-type': 'multipart/form-data'
                }
            };
            let form = new FormData();
            // let resultObj = {
            //     date = this.date,
            //     file = this.file
            // }
            form.append('date', item.date);
            form.append('file', this.file);
            form.append('labexam_id', item.id);
            /*Object.keys(resultObj).forEach(function(key) {

                form.append(key, resultObj[key]);
            });*/
              this.loader = true;
              this.$http.post(this.url +'/'+ this.patient_id +'/labresults', form, config).then((response) => {
    
                  if(response.status == 200)
                  {
                    this.loadResults();
                    bus.$emit('alert', 'Resultado Agregado','success');
                   
                  }
                  this.loader = false;
                  this.file = "";
              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el Resultado', 'danger');
                  this.loader = false;
              });
             bus.$emit('clearImage');
            

          },
          add() {

            let form = new FormData();
          
            form.append('date', this.date);
            form.append('name', this.name);
            form.append('appointment_id', this.appointment_id);
             
            
              this.$http.post(this.url +'/'+ this.patient_id +'/labexams', form).then((response) => {
                 
                  if(response.status == 200)
                  {
                   this.loadResults()
                    bus.$emit('alert', 'Examen Agregado','success');
                   
                  }
                  this.loader = false;
                  this.name = "";
              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el Examen', 'danger');
                  this.loader = false;
              });
             
            

          },
          removeResult(item){
            let fileToDelete = "/patients/"+ this.patient_id +"/labresults/"+ item.id +"/"+ item.name; 
            
            if(this.loader) return

            this.loader = true;
            this.$http.delete(this.url +'/labresults/'+item.id,{ body: { file: fileToDelete }}).then((response) => {

                  if(response.status == 200)
                  {
                     this.loadResults();
                    bus.$emit('alert', 'Resultado Eliminado','success');
                  }
                  this.loader = false;
              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar el Resultado', 'danger');
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
                  this.loader = false;
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

                
                })
          },
           handleFileUpload(file){
                console.log(file)
                this.file = file
                
            }

      },
      created () {
           console.log('Component ready. Lab Results.')
          
            this.loadResults();
          
            this.date = moment().format("YYYY-MM-DD");
           
      }
      
    }
</script>