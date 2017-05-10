<template>	
  <div class="box">
                
              <div class="box-body">
                

                
                <div v-for="q in encuesta.questions" class="col-lg-4 col-sm-6 col-xs-12"> 
                  <p>{{ q.name }}</p>
                  <div class="radio" v-for="op in q.answers">
                    <input type="radio" name="rate" v-model="rate" :value="op.id" :id="op.id" :disabled="q.completed == 1">
                    <label :for="op.id">{{  op.name }}</label>
                    
                    
                  </div>
             
                  <input type="submit" :value="q.completed == 1 ? 'Enviada' : 'Guardar'" class="btn btn-info" @click="save(q)" :disabled="q.completed == 1">
                </div>
                
             
                
                </div>
                
            </div>
  
</template>

<script>
    
    export default {
      props: ['poll'],
      data () {
        return {
           encuesta:{},
           questions:[],
           rate:'',
           answers:[],
        }
      },
     
       methods: {
       
         save(question){
            
          if(question.completed == 1){
              bus.$emit('alert', 'La Pregunta ya fue contestada!','danger');
            return false;
          }
             var resource = this.$resource('/polls/'+this.encuesta.id);
                
                resource.update({rate: this.rate, question: question.id}).then((response) => {
                    if(response.status == 200 && response.data)
                    {
                     
                     bus.$emit('alert', 'Respuesta Enviada','success');
                     var index =this.encuesta.questions.indexOf(question)
                     this.encuesta.questions[index].completed = 1;
                     this.rate = '';
                   }
                }, (response) => {
                    console.log(response.data)
                    
                });

          


         } //save
      },
      created () {
             console.log('Component ready. Poll Clinic')

             if(this.poll)
               this.encuesta = this.poll;
            
            
        }
    }
</script>