<template>
  <div>
      
      <ul class="todo-list">
        <li>
          <i class="fa fa-arrows-v"></i>
          Altura 
          <div class="pull-right" :class="errors.body ? ' has-error' : ''">
            <span style="margin-right: 5px;">Mts</span>
            <input type="text" name="height" v-model="vital_signs.height" class="form-control pull-right" style="width:50px;height:25px;" @keydown="keydown()" />
             
              <form-error v-if="errors.height" :errors="errors" style="float:right;">
                  {{ errors.height[0] }}
              </form-error>
          </div>
        </li>
        <li>
          <i class="fa fa-tachometer"></i>
          Peso
          <div class="pull-right">
            <span style="margin-right: 5px;">Kg</span>
            <input type="text" v-model="vital_signs.weight" class="form-control pull-right" style="width:50px;height:25px;" @keydown="keydown()" /> <form-error v-if="errors.weight" :errors="errors" style="float:right;">
                  {{ errors.weight[0] }}
              </form-error>
          </div>
        </li>
        <li>
          <i class="fa fa-male"></i>
          Masa Corporal 
          <div class="pull-right">
            <span style="margin-right: 5px;">Imc</span>
            <input type="text" v-model="vital_signs.mass" class="form-control pull-right" style="width:50px;height:25px;" @keydown="keydown()" /> 
            <form-error v-if="errors.mass" :errors="errors" style="float:right;">
                  {{ errors.mass[0] }}
              </form-error>
          </div>
        </li>
        <li>
          <i class="fa fa-fire"></i> 
          Temperatura 
          <div class="pull-right">
            <span style="margin-right: 5px;">C</span>
            <input type="text" v-model="vital_signs.temp" class="form-control pull-right" style="width:50px;height:25px;" @keydown="keydown()" /> 
             <form-error v-if="errors.temp" :errors="errors" style="float:right;">
                  {{ errors.temp[0] }}
              </form-error>
          </div>
        </li>
        <li>
          <i class="fa fa-mail-forward"></i>
          Frecuencia Respiratoria 
          <div class="pull-right">
            <span style="margin-right: 5px;">r/m</span> 
            <input type="text" v-model="vital_signs.respiratory_rate" class="form-control pull-right" style="width:50px;height:25px;" @keydown="keydown()" />
            <form-error v-if="errors.respiratory_rate" :errors="errors" style="float:right;">
                  {{ errors.respiratory_rate[0] }}
              </form-error>
          </div>
        </li>
        <li>
          <i class="fa fa-battery-half"></i> 
          Presión Arterial 
          <div class="pull-right">
            <span style="margin-right: 5px;">mm/hg</span>
            <input type="text" v-model="vital_signs.blood" class="form-control pull-right" style="width:50px;height:25px;" @keydown="keydown()" /> 
            <form-error v-if="errors.blood" :errors="errors" style="float:right;">
                  {{ errors.blood[0] }}
              </form-error>
          </div>
        </li>
        <li>
          <i class="fa fa-heartbeat"></i> 
          Frecuencia Cardiaca 
          <div class="pull-right">
            <span style="margin-right: 5px;">l/m</span>
            <input type="text" v-model="vital_signs.heart_rate" class="form-control pull-right" style="width:50px;height:25px;" @keydown="keydown()" />
            <form-error v-if="errors.heart_rate" :errors="errors" style="float:right;">
                  {{ errors.heart_rate[0] }}
              </form-error>
          </div>
        </li>
      </ul>
        
  </div>
             
</template>

<script>

    import FormError from './FormError.vue';

    export default {
      props: ['signs'],
      data () {
        return {
          vital_signs: {
            height: 0,
            weight: 0,
            mass: 0,
            temp: 0,
            respiratory_rate: 0,
            blood: 0,
            heart_rate:0

          },
          loader:false,
          loader_message:"",
          errors: []
          
 
        }
      },
      components:{
        FormError
      },
      methods: {

         keydown :_.debounce(
          function ()  {

              if(this.vital_signs.height == "")
                  this.vital_signs.height = 0;

              if(this.vital_signs.weight == "")
                  this.vital_signs.weight = 0;

              if(this.vital_signs.mass == "")
                  this.vital_signs.mass = 0;

              if(this.vital_signs.temp == "")
                  this.vital_signs.temp = 0;

              if(this.vital_signs.respiratory_rate == "")
                  this.vital_signs.respiratory_rate = 0;

              if(this.vital_signs.blood == "")
                  this.vital_signs.blood = 0;

              if(this.vital_signs.heart_rate == "")
                  this.vital_signs.heart_rate = 0;

              this.update();
            },
        500
        ),
         
         update () {
            //this.loader = true;
            this.loader_message = "Guardando..."
            var resource = this.$resource('/medic/signs/'+ this.signs.id);

                resource.update(this.vital_signs).then((response) => {
                    
                     this.loader_message ="Cambios Guardados";
                     this.loader = false;
                     this.errors = [];
                }, (response) => {
                    console.log(response.data)
                    this.loader = false;
                    this.loader_message ="Error al guardar cambios";
                    this.errors = response.data;
                });
          }
         
     
      },
      created () {
         
           console.log('Component ready. Vital Signs')

           this.vital_signs = this.signs;
          
           
          
      }
    }
</script>