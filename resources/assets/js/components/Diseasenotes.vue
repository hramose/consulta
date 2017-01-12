<template>
  <div>
      <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">RAZÓN DE LA VISITA</h3> <small class="pull-right">{{ loader_message }}</small>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <textarea name="reason" cols="30" rows="4" class="form-control" v-model="disease_notes.reason" @keydown="keydown()"></textarea>
            </div>
            <!-- /.box-body -->
            <div class="overlay" v-show="loader">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
      </div>
      <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">SÍNTOMAS SUBJETIVOS</h3>  <small class="pull-right">{{ loader_message }}</small>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <textarea name="symptoms" cols="30" rows="4" class="form-control" v-model="disease_notes.symptoms" @keydown="keydown()" ></textarea>
            </div>
            <!-- /.box-body -->
            <div class="overlay" v-show="loader">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
      </div>
      <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">EXPLORACIÓN FÍSICA</h3> <small class="pull-right">{{ loader_message }}</small>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <textarea name="phisical_review" cols="30" rows="4" class="form-control" v-model="disease_notes.phisical_review" @keydown="keydown()"></textarea>
            </div>
            <!-- /.box-body -->
            <div class="overlay" v-show="loader">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
      </div>
        
  </div>
             
</template>

<script>

    export default {
      props: ['notes'],
      data () {
        return {
          disease_notes: {
            reason:"",
            symptoms:"",
            phisical_review: ""
          },
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
            //this.loader = true;
            this.loader_message = "Guardando..."
            var resource = this.$resource('/medic/diseasenotes/'+ this.notes.id);

                resource.update({data: this.disease_notes}).then((response) => {
                      
                      
                    bus.$emit('actSummaryNotes', this.disease_notes);
                     this.loader_message ="Cambios Guardados";
                     this.loader = false;
                }, (response) => {
                    console.log('error al actualizar notas de padecimiento')
                    this.loader = false;
                    this.loader_message ="Error al guardar cambios";
                });
          }
         
     
      },
      created () {
         
           console.log('Component ready. DiseaseNotes')

           this.disease_notes = this.notes;
          
           
          
      }
    }
</script>