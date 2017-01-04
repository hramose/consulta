<template>
	
  <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
          
          <div class="panel box box-primary">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#alergias" aria-expanded="false" class="collapsed">
                  ALERGIAS: <small><span v-for="item in activesAlergias" v-text="item.value" class="label label-primary activesHistory"></span></small>
                </a>
              </h4>
            </div>
            <div id="alergias" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                 
                 <div v-for="item in dataHistories.alergias">
                  <textarea name="alergias" cols="30" rows="3" class="form-control" v-model="item.value"></textarea>
                  <div class="form-group pull-right">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success"  @click="updateHistory()">Guardar</button>
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="panel box box-danger">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#patologicos" class="collapsed" aria-expanded="false">
                  ANTECEDENTES PATOLÓGICOS: <small> <span v-for="item in activesPatologicos" v-text="item.name" class="label label-danger activesHistory"></span> </small>
                </a>
              </h4>
            </div>
            <div id="patologicos" class="panel-collapse collapse" aria-expanded="false" >
              <div class="box-body">
                  
                 <history-item v-for="item in dataHistories.patologicos" :item="item" @update="updateHistory()" colorBox="box-danger"></history-item>
                  
              </div>
            </div>
          </div>
        
          <div class="panel box box-success">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#no_patologicos" class="collapsed" aria-expanded="false" >
                  ANTECEDENTES NO PATOLÓGICOS: <small> <span v-for="item in activesNoPatologicos" v-text="item.name" class="label label-success activesHistory"></span>  </small>
                </a>
              </h4>
            </div>
            <div id="no_patologicos" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                
                <history-item v-for="item in dataHistories.no_patologicos" :item="item" @update="updateHistory()" colorBox="box-success"></history-item>
                  
              </div>
            </div>
          </div>

           <div class="panel box box-warning">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#heredofamiliares" class="collapsed" aria-expanded="false">
                  ANTECEDENTES HEREDOFAMILIARES: <small> <span v-for="item in activesHeredo" v-text="item.name" class="label label-warning activesHistory"></span>  </small>
                </a>
              </h4>
            </div>
            <div id="heredofamiliares" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                
                <history-item v-for="item in dataHistories.heredofamiliares" :item="item" @update="updateHistory()" colorBox="box-warning"></history-item>
                
              </div>
            </div>
          </div>
          
           <div class="panel box box-default">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#gineco_obstetricios" class="collapsed" aria-expanded="false">
                  ANTECEDENTES GINECO-OBSTETRICIOS: <small> <span v-for="item in activesGineco" v-text="item.name" class="label label-default activesHistory"></span>  </small>
                </a>
              </h4>
            </div>
            <div id="gineco_obstetricios" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">

                 <history-item v-for="item in dataHistories.gineco_obstetricios" :item="item" @update="updateHistory()" colorBox="box-default"></history-item>
                
              </div>
            </div>
          </div>
</div>
             
</template>

<script>

    import HistoryItem from './HistoryItem.vue';

    export default {
      props: ['history'],
      
      data () {
        return {

          dataHistories: {
            version: "1.0",
            alergias: [{
              name: 'Alergias',
              value: ""
            }],
            patologicos:[{
              name:"Hospitalizacion Previa",
              value: ""
            },
            {
              name:"Cirugías Previas",
              value: ""
            },
            {
              name:"Diabetes",
              value: ""
            },
            {
              name: "Enfermedades Tiroideas",
              value: ""
            },
            {
              name: "Hipertensión Arterial",
              value: ""
            },
            {
              name: "Cardiopatías",
              value: ""
            },
            {
              name: "Traumatismos",
              value: ""
            },
            {
              name: "Cáncer",
              value: ""
            },
            {
              name: "Tuberculosis",
              value: ""
            },
            {
              name: "Transfusiones",
              value: ""
            },
            {
              name: "Otros (patologicos)",
              value: ""
            }],
            no_patologicos:[{
              name:"Actividad Física",
              value: ""
            },
            {
              name:"Tabaquismo",
              value: ""
            },
            {
              name:"Alcoholismo",
              value: ""
            },
            {
              name:"Uso de otras sustancias (Drogas)",
              value: ""
            },
            {
              name:"Otros (No Patológicos)",
              value: ""
            }],
            heredofamiliares:[{
               name:"Diabetes (Heredofamiliares)",
               value: ""
            },
            {
               name:"Cardiopatías (Heredofamiliares)",
               value: ""
            },
            {
               name:"Hipertensión Arterial (Heredofamiliares)",
               value: ""
            },
            {
               name:"Enfermedades Tiroideas (Heredofamiliares)",
               value: ""
            },
            {
               name:"Otros (Heredofamiliares)",
               value: ""
            }],
            gineco_obstetricios:[
            {
               name:"Fecha de primera menstruación",
               value: ""
            },
            {
               name:"Fecha de última menstruación",
               value: ""
            },
            {
               name:"Características menstruación",
               value: ""
            },
            {
               name:"Embarazos",
               value: ""
            },
            {
               name:"Cáncer Cérvico",
               value: ""
            },
            {
               name:"Cáncer Uterino",
               value: ""
            },
            {
               name:"Cáncer de Mama",
               value: ""
            },
            {
               name:"Otros (Gineco-Obstetricios)",
               value: ""
            }

            ],
        
          },
          loader:false

        }
          
      },
      computed:{
        activesAlergias(){

            return this.dataHistories.alergias.filter(item => item.value);
        },
        activesPatologicos(){

            return this.dataHistories.patologicos.filter(item => item.value);
        },
        activesNoPatologicos(){

            return this.dataHistories.no_patologicos.filter(item => item.value);
        },
        activesHeredo(){

            return this.dataHistories.heredofamiliares.filter(item => item.value);
        },
        activesGineco(){

            return this.dataHistories.gineco_obstetricios.filter(item => item.value);
        }


      },
      methods: {
          
          updateHistory () {

            var resource = this.$resource('/patients{/id}/history');

                resource.update({id: this.history.patient_id}, {data: JSON.stringify(this.dataHistories)}).then((response) => {
                     
                      if(response.status === 200)
                      {
                        bus.$emit('alert', response.data,'success');
                        bus.$emit('actSummaryHistory', this.dataHistories);
                      }

                     this.loader = false;
                }, (response) => {
                    console.log('error al actualizar history')
                    bus.$emit('alert', 'Error al actualizar el historial medico', 'danger');
                    this.loader = false;
                });
          }
         
     
      },
      components:{
        HistoryItem
      },
      created () {
           console.log('Component ready. history.')
          
           if(this.history.histories)
           {
            
                this.dataHistories = JSON.parse(this.history.histories);
                 
                /*if(this.dataHistories.version === "1.0")
                {
                  
                  this.dataHistories.alergias.push({
                      name: 'Alergias-2',
                      value: ""
                    });

                  this.dataHistories.version = "1.2";
                  
                  
                }*/
            
           }


           
          
      }
}
</script>