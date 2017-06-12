<template>	
  <div class="box">
                
              <div class="box-body">
                
                  <p>Satisfaccion de la consulta</p>
                  <div id="star" class="ratings"></div>
                  <div class="form-group">
                    
                    <textarea cols="30" rows="10" v-model="review1.comments"></textarea>
                  </div>
                  
                <div class="form-group">
                  <input type="submit" :value="Guardar" class="btn btn-info" @click="save(review1)"><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
               </div>
             
                
                </div>
                
            </div>
  
</template>

<script>
    
    export default {
      props: ['poll'],
      data () {
        return {
           review1:{
              comments:'',
              score:''
           },
            review2:{
              comments:'',
              score:''
           },
           loader: false
           
        }
      },
     
       methods: {
       
         save(question){
           
          if(question.completed == 1){
              bus.$emit('alert', 'La Pregunta ya fue contestada!','danger');
            return false;
          }
            this.loader = true;
             var resource = this.$resource('/polls/'+this.encuesta.id);
                
                resource.update({rate: this.rate, question: question.id}).then((response) => {
                    if(response.status == 200 && response.data)
                    {
                     
                     bus.$emit('alert', 'Respuesta Enviada','success');
                     var index =this.encuesta.questions.indexOf(question)
                     this.encuesta.questions[index].completed = 1;
                     this.rate = '';
                   }
                   this.loader = false;
                }, (response) => {
                    console.log(response.data)
                    this.loader = false;
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