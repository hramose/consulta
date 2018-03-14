<template>
	
<div class="box box-success ">
          <div class="box-header with-border">
            <h3 class="box-title">Resultados</h3>

            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
                <div class="form-group" v-show="!read">
                    <div class="input-group">
                          <input type="text" class="form-control"  id="datetimepickerLabResult" v-model="date" @blur="onBlurDatetime">

                          <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                          </div>
                      </div>
                    
                </div>
                <div class="form-group" v-show="!read">
                    <photo-upload @input="handleFileUpload" :value="value"></photo-upload>
                    <form-error v-if="errors.file" :errors="errors" style="float:right;">
                        {{ errors.file[0] }}
                    </form-error>
                </div>
                <div class="form-group">
                          <button @click="hit" class="btn btn-success" v-show="!read" v-bind:disabled="loader">Agregar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader"> 
                  </div>
                <ul id="medicines-list" class="todo-list ui-sortable" v-show="dataResults.length">
                
                  <li v-for="item in dataResults">
                    <!-- todo text -->
                    <span><span class="text"> {{ formatDate(item.date) }}</span> <a v-bind:href="'/storage/patients/'+ patient_id +'/labresults/'+ item.id +'/'+ item.name " target="_blank">{{ item.name}}</a></span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                      
                      <i class="fa fa-trash-o delete" @click="remove(item)" v-show="!read" v-bind:disabled="loader"></i>
                    </div>
                  </li>
                
                </ul>
        </div>
      
                
    
</div>
  
</template>

<script>
 import FormError from './FormError.vue';
    export default {
      //props: ['medicines','patient_id'],
       props: {
         patient_id: {
          type: Number
          
        },
        results: {
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
          dataResults:[],
          loader:false,
          errors:[]

        }
          
      },
      components:{
        FormError
       
      },
      methods: {
        slug(str){
          str = str.replace(/^\s+|\s+$/g, ''); // trim
          str = str.toLowerCase();

          // remove accents, swap ñ for n, etc
          var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
          var to   = "aaaaaeeeeeiiiiooooouuuunc------";
          for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
          }

          str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

          return str;
        },
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
            if(!this.date || !this.file)
              return

            this.loader = true;
            this.add();
            
          },
          add() {

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
            form.append('date', this.date);
            form.append('file', this.file);
            
            /*Object.keys(resultObj).forEach(function(key) {

                form.append(key, resultObj[key]);
            });*/
            
              this.$http.post(this.url +'/'+ this.patient_id +'/labresults', form, config).then((response) => {
    
                   if(response.status == 200 || response.status == 201)
                  {
                    this.dataResults.unshift(response.data);
                    bus.$emit('alert', 'Resultado Agregado','success');
                   
                  }
                  this.loader = false
                  this.file = "";
                  this.errors = [];
              }, (response) => {
                  // let message = 'Error al guardar el Resultado'
               
                  // if(response.status == 422)
                  //   message = response.body.errors.file[0]

                  //bus.$emit('alert',message , 'danger');
                  this.loader = false;
                  this.errors = response.data.errors;
                  this.file = ''
              });
             bus.$emit('clearImage');
            

          },
          remove(item){
            let fileToDelete = "/patients/"+ this.patient_id +"/labresults/"+ item.id +"/"+ item.name; 

            this.loader = true;
            this.$http.delete(this.url +'/labresults/'+item.id,{ body: { file: fileToDelete }}).then((response) => {

                   if(response.status == 200 || response.status == 201)
                  {
                     var index = this.dataResults.indexOf(item)
                    this.dataResults.splice(index, 1);
                    bus.$emit('alert', 'Resultado Eliminado','success');
                  }
                  this.loader = false;
              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar el Resultado', 'danger');
                  this.loader = false;
              });


          },
           handleFileUpload(file){
                console.log(file)
                this.file = file
                
            }

      },
      created () {
           console.log('Component ready. Lab Results.')
          
           if(this.results.length)
           {
            
                this.dataResults = this.results;
            }

             this.date = moment().format("YYYY-MM-DD");
           
      }
      
    }
</script>