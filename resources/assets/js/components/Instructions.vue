<template>
 
      <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Recomendaciones Médicas</h3> <small class="pull-right">{{ loader_message }}</small>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <textarea name="medical_instructions" cols="30" rows="4" class="form-control" v-model="medical_instructions" @keydown="keydown()" :readonly="read"></textarea>
            </div>
            <!-- /.box-body -->
      </div>


             
</template>

<script>

    export default {
      //props: ['appointment'],
      props: {
        appointment: {
          type: Object
        },
        read:{
          type:Boolean,
          default: false
        }
      },
      data () {
        return {
          medical_instructions: "",
          loader:false,
          loader_message:""
          
 
        }
      },
      methods: {

         keydown :_.debounce(
          function ()  {
              this.update();
            },
        500
        ),
         
         update () {
            this.loader_message = "Guardando...";
            var resource = this.$resource('/medic/appointments{/id}/');

                resource.update({ id: this.appointment.id },{medical_instructions: this.medical_instructions}).then((response) => {
                      console.log(response.status);
                     
                      
                      bus.$emit('actSummaryInstructions', this.medical_instructions);
                    
                     this.loader = false;
                     this.loader_message = "Cambios Guardados";
                }, (response) => {
                    console.log('error al actualizar notas de instructions')
                    this.loader = false;
                    this.loader_message = "error al actualizar notas de instructions";
                });
          }
         
     
      },
      created () {
         
           console.log('Component ready. Instructions')

           this.medical_instructions = this.appointment.medical_instructions;
           
           
          
      }
    }
</script>