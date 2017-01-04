<template>
   
   <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="row">
          
       
          <div class="col-md-4">
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#cardio" aria-expanded="false" class="collapsed">
                    Cardiaco y Vascular
                  </a>
                </h4>
              </div>
              <div id="cardio" class="panel-collapse collapse" :class="(physical_exams.cardio) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="cardio" cols="30" rows="3" class="form-control" v-model="physical_exams.cardio" @keydown="keydown()"></textarea>
                    
                </div>
               
              </div>
              
            </div>
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#linfatico" aria-expanded="false" class="collapsed">
                    Sistema Linfático
                  </a>
                </h4>
              </div>
              <div id="linfatico" class="panel-collapse collapse" :class="(physical_exams.linfatico) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="linfatico" cols="30" rows="3" class="form-control" v-model="physical_exams.linfatico" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#osteoarticular" aria-expanded="false" class="collapsed">
                    Osteoarticular
                  </a>
                </h4>
              </div>
              <div id="osteoarticular" class="panel-collapse collapse" :class="(physical_exams.osteoarticular) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="osteoarticular" cols="30" rows="3" class="form-control" v-model="physical_exams.osteoarticular" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#psiquiatrico" aria-expanded="false" class="collapsed">
                    Psiquiátrico y Psicológico
                  </a>
                </h4>
              </div>
              <div id="psiquiatrico" class="panel-collapse collapse" :class="(physical_exams.psiquiatrico) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="psiquiatrico" cols="30" rows="3" class="form-control" v-model="physical_exams.psiquiatrico" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
          </div>
          <div class="col-md-4">
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#digestivo" aria-expanded="false" class="collapsed">
                    Aparato Digestivo
                  </a>
                </h4>
              </div>
              <div id="digestivo" class="panel-collapse collapse" :class="(physical_exams.digestivo) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="cardio" cols="30" rows="3" class="form-control" v-model="physical_exams.digestivo" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#dermatologico" aria-expanded="false" class="collapsed">
                    Dermatológico
                  </a>
                </h4>
              </div>
              <div id="dermatologico" class="panel-collapse collapse" :class="(physical_exams.dermatologico) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="dermatologico" cols="30" rows="3" class="form-control" v-model="physical_exams.dermatologico" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#otorrinolaringologico" aria-expanded="false" class="collapsed">
                    Otorrinolaringológico
                  </a>
                </h4>
              </div>
              <div id="otorrinolaringologico" class="panel-collapse collapse" :class="(physical_exams.otorrinolaringologico) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="otorrinolaringologico" cols="30" rows="3" class="form-control" v-model="physical_exams.otorrinolaringologico" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#reproductor" aria-expanded="false" class="collapsed">
                    Aparato Reproductor
                  </a>
                </h4>
              </div>
              <div id="reproductor" class="panel-collapse collapse" :class="(physical_exams.reproductor) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="reproductor" cols="30" rows="3" class="form-control" v-model="physical_exams.reproductor" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
          </div>
          <div class="col-md-4">
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#urinario" aria-expanded="false" class="collapsed">
                    Aparato Urinario
                  </a>
                </h4>
              </div>
              <div id="urinario" class="panel-collapse collapse" :class="(physical_exams.urinario) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="urinario" cols="30" rows="3" class="form-control" v-model="physical_exams.urinario" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#neurologico" aria-expanded="false" class="collapsed">
                    Neurológico
                  </a>
                </h4>
              </div>
              <div id="neurologico" class="panel-collapse collapse" :class="(physical_exams.neurologico) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="neurologico" cols="30" rows="3" class="form-control" v-model="physical_exams.neurologico" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
            <div class="panel box box-primary">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#pulmonar" aria-expanded="false" class="collapsed">
                    Pulmonar o Respiratorio
                  </a>
                </h4>
              </div>
              <div id="pulmonar" class="panel-collapse collapse" :class="(physical_exams.pulmonar) ? 'in' : '' " aria-expanded="false">
                <div class="box-body">
                  
                    <textarea name="pulmonar" cols="30" rows="3" class="form-control" v-model="physical_exams.pulmonar" @keydown="keydown()"></textarea>
                    
                 
                  
                </div>
               
              </div>
              
            </div>
          </div>
      </div>
      <small >{{ loader_message }}</small>
  </div>
	
  <!--<div class="form-group">
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.cardio" v-model="checkboxes.cardio"> Cardiaco
     
      <textarea name="cardio" cols="30" rows="4" class="form-control" v-model="physical_exams.cardio" @keydown="keydown()" ></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.digestivo" v-model="checkboxes.digestivo"> digestivo
      <textarea name="digestivo" cols="30" rows="4" class="form-control" v-model="physical_exams.digestivo" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.urinario" v-model="checkboxes.urinario"> urinario
      <textarea name="urinario" cols="30" rows="4" class="form-control" v-model="physical_exams.urinario" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.linfatico" v-model="checkboxes.linfatico"> linfatico
      <textarea name="linfatico" cols="30" rows="4" class="form-control" v-model="physical_exams.linfatico" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.dermatologico" v-model="checkboxes.dermatologico"> dermatologico
      <textarea name="dermatologico" cols="30" rows="4" class="form-control" v-model="physical_exams.dermatologico" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.neurologico" v-model="checkboxes.neurologico"> neurologico
      <textarea name="neurologico" cols="30" rows="4" class="form-control" v-model="physical_exams.neurologico" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.osteoarticular" v-model="checkboxes.osteoarticular"> osteoarticular
      <textarea name="osteoarticular" cols="30" rows="4" class="form-control" v-model="physical_exams.osteoarticular" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.otorrinolaringologico" v-model="checkboxes.otorrinolaringologico"> otorrinolaringologico
      <textarea name="otorrinolaringologico" cols="30" rows="4" class="form-control" v-model="physical_exams.otorrinolaringologico" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.pulmonar" v-model="checkboxes.pulmonar"> pulmonar
      <textarea name="pulmonar" cols="30" rows="4" class="form-control" v-model="physical_exams.pulmonar" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.psiquiatrico" v-model="checkboxes.psiquiatrico"> psiquiatrico
      <textarea name="psiquiatrico" cols="30" rows="4" class="form-control" v-model="physical_exams.psiquiatrico" @keydown="keydown()"></textarea>
    </label>
    <label>
      <input type="checkbox" class="flat-red" :checked="physical_exams.reproductor" v-model="checkboxes.reproductor"> reproductor
      <textarea name="reproductor" cols="30" rows="4" class="form-control" v-model="physical_exams.reproductor" @keydown="keydown()"></textarea>
    </label>
  </div>-->
             
</template>

<script>
    export default {
      props: ['physical'],
      data () {
        return {

          physical_exams: {
            cardio:"",
            digestivo:"",
            urinario: "",
            linfatico: "",
            dermatologico: "",
            neurologico: "",
            osteoarticular: "",
            otorrinolaringologico: "",
            pulmonar: "",
            psiquiatrico: "",
            reproductor: ""

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
            var resource = this.$resource('/physicalexams/'+ this.physical.id);

                resource.update({data: this.physical_exams}).then((response) => {

                      bus.$emit('actSummaryExams', this.physical_exams);
                    
                     this.loader = false;
                     this.loader_message = "Cambios Guardados"
                }, (response) => {
                    console.log('error al actualizar examenes fisicos')
                    this.loader = false;
                    this.loader_message ="Error al guardar cambios";
                });
          }
         
      },
      created () {
           console.log('Component ready. exams.')
         
            
          this.physical_exams = this.physical
                 
        

           
          
      }
    }
</script>