<template>
	
  <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
          
          <div class="panel box box-info" v-show="appointments">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#diagnosticos" aria-expanded="false" class="collapsed">
                   DIAGNOSTICOS DE CONSULTAS ANTERIORES: 
                </a>
              </h4>
            </div>
            <div id="diagnosticos" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                 
                 <h3 v-show ="diagnosticsToday.length">Diagnósticos de la consulta actual</h3>
                 <ul id="medicines-list" class="todo-list ui-sortable" v-show="diagnosticsToday.length">
       
                  <li v-for="item in diagnosticsToday" >
                    <!-- todo text -->
                    
                    <span><span class="text"> {{ item.name }}</span></span>
                     
                    <!-- General tools such as edit or delete-->
                    <!-- <div class="tools">
                      <span>Dr. {{ appointment.user.name }} </span>
                      
                    </div> -->
                  </li>
                 
                </ul>
                 <ul id="medicines-list" class="todo-list ui-sortable" v-show="diagnostics.length">
       
                  <li v-for="appointment in diagnostics" v-show="appointment.diagnostics.length">
                    <!-- todo text -->
                    
                    <span><span class="text"> {{ appointment.created_at }} - <span>Dr. {{ appointment.user.name }} </span></span></span>
                      <ul>
                        <li v-for="diagnostic in appointment.diagnostics">
                          <span><span class="text"> {{ diagnostic.name }}</span></span>
                        </li>
                      </ul>
                    <!-- General tools such as edit or delete-->
                    <!-- <div class="tools">
                      <span>Dr. {{ appointment.user.name }} </span>
                      
                    </div> -->
                  </li>
                 
                </ul>
               
                
              </div>
            </div>
          </div>
          <div class="panel box box-primary">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#alergias" aria-expanded="false" class="collapsed">
                  ALERGIAS: <small><span class="label label-primary activesHistory">{{ allergies.length }}</span></small>
                </a>
              </h4>
            </div>
            <div id="alergias" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                 
                 <ul id="medicines-list" class="todo-list ui-sortable" v-show="allergies.length">
       
                  <li v-for="item in allergies">
                    <!-- todo text -->
                    <span><span class="text"> {{ item.name }}</span></span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                      <span>Agregado por: {{ item.user.name }} - </span>
                      <span>{{ item.created_at }}</span>
                    </div>
                  </li>
                 
                </ul>
                <div v-show="!read">
                  <h4>Agregar nueva alergia:</h4>
                  <textarea name="alergias" cols="30" rows="3" class="form-control" v-model="allergy"></textarea>
                  <div class="form-group pull-right">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success"  @click="saveHistory('/medic/allergies',allergy,'allergy')">Guardar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
                    </div>
                  </div>
                  
                </div>
                
              </div>
            </div>
          </div>
          <div class="panel box box-danger">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#patologicos" aria-expanded="false" class="collapsed">
                  ANTECEDENTES PATOLÓGICOS: <small><span class="label label-danger activesHistory">{{ pathologicals.length }}</span></small>
                </a>
              </h4>
            </div>
            <div id="patologicos" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                 
                 <ul id="medicines-list" class="todo-list ui-sortable" v-show="pathologicals.length">
       
                  <li v-for="item in pathologicals">
                    <!-- todo text -->
                    <span><span class="text"> {{ item.name }}</span></span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                      <span>Dr. {{ item.user.name }} - </span>
                      <span>{{ item.created_at }}</span>
                    </div>
                  </li>
                 
                </ul>
                <div v-show="!read">
                     <h4>Agregar nuevo:</h4>
                    <textarea name="pathological" cols="30" rows="3" class="form-control" v-model="pathological" placeholder="Ej: Hospitalizacion previa, Cirugías, Diabetes, Enfermedades Tiroideas, Hipertensión Arterial, etc."></textarea>
                    <div class="form-group pull-right">
                      <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success"  @click="saveHistory('/medic/pathologicals',pathological,'pathological')">Guardar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
                      </div>
                    </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="panel box box-success">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#no_patologicos" aria-expanded="false" class="collapsed">
                  ANTECEDENTES NO PATOLÓGICOS: <small><span class="label label-success activesHistory">{{ no_pathologicals.length }}</span></small>
                </a>
              </h4>
            </div>
            <div id="no_patologicos" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                 
                 <ul id="medicines-list" class="todo-list ui-sortable" v-show="no_pathologicals.length">
       
                  <li v-for="item in no_pathologicals">
                    <!-- todo text -->
                    <span><span class="text"> {{ item.name }}</span></span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                      <span>Dr. {{ item.user.name }} - </span>
                      <span>{{ item.created_at }}</span>
                    </div>
                  </li>
                 
                </ul>
                <div v-show="!read">
                   <h4>Agregar nuevo:</h4>
                  <textarea name="no_pathological" cols="30" rows="3" class="form-control" v-model="no_pathological" placeholder="Ej: Actividad Física, Tabaquismo, Alcoholismo, Uso de otras sustancias (Drogas), etc."></textarea>
                  <div class="form-group pull-right">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success"  @click="saveHistory('/medic/nopathologicals',no_pathological,'no_pathological')">Guardar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="panel box box-warning">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#heredofamiliares" aria-expanded="false" class="collapsed">
                   ANTECEDENTES HEREDOFAMILIARES: <small><span class="label label-warning activesHistory">{{ heredos.length }}</span></small>
                </a>
              </h4>
            </div>
            <div id="heredofamiliares" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                 
                 <ul id="medicines-list" class="todo-list ui-sortable" v-show="heredos.length">
       
                  <li v-for="item in heredos">
                    <!-- todo text -->
                    <span><span class="text"> {{ item.name }}</span></span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                      <span>Dr. {{ item.user.name }} - </span>
                      <span>{{ item.created_at }}</span>
                    </div>
                  </li>
                 
                </ul>
                <div v-show="!read">
                   <h4>Agregar nuevo:</h4>
                  <textarea name="heredo" cols="30" rows="3" class="form-control" v-model="heredo" placeholder="Ej: Diabetes, Cardiopatías, Hipertensión Arterial, etc."></textarea>
                  <div class="form-group pull-right">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success"  @click="saveHistory('/medic/heredos',heredo,'heredo')">Guardar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="panel box box-default">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#gineco_obstetricios" aria-expanded="false" class="collapsed">
                   ANTECEDENTES GINECO-OBSTETRICIOS: <small><span class="label label-default activesHistory">{{ ginecos.length }}</span></small>
                </a>
              </h4>
            </div>
            <div id="gineco_obstetricios" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                 
                 <ul id="medicines-list" class="todo-list ui-sortable" v-show="ginecos.length">
       
                  <li v-for="item in ginecos">
                    <!-- todo text -->
                    <span><span class="text"> {{ item.name }}</span></span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                      <span>Dr. {{ item.user.name }} - </span>
                      <span>{{ item.created_at }}</span>
                    </div>
                  </li>
                 
                </ul>
                <div v-show="!read">
                   <h4>Agregar nuevo:</h4>
                  <textarea name="gineco" cols="30" rows="3" class="form-control" v-model="gineco" placeholder="Ej: Fecha de primera menstruación, Fecha de última menstruación, Características menstruación, Embarazos, etc."></textarea>
                  <div class="form-group pull-right">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success"  @click="saveHistory('/medic/ginecos',gineco,'gineco')">Guardar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
          
          
</div>
             
</template>

<script>

    import HistoryItem from './HistoryItem.vue';

    export default {
      //props: ['history','appointments'],
      props: {
        appointments: {
          type: Array
        },
        history: {
          type: Object
        },
        read:{
          type:Boolean,
          default: false
        }
      },
      data () {
        return {
           allergy:"",
           pathological:"",
           no_pathological:"",
           heredo:"",
           gineco:"",
           allergies:[],
           pathologicals:[],
           no_pathologicals:[],
           heredos:[],
           ginecos:[],
           diagnostics:[],
           diagnosticsToday:[],
          
          loader:false

        }
          
      },
      computed:{
        /*activesAlergias(){

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
        }*/


      },
      methods: {

         saveHistory(url,item,cat) {

            
            this.loader = true;
            this.$http.post(url, {history_id: this.history.id, name: item}).then((response) => {

                  
                  console.log(response);
                  

                  if(response.status == 200)
                  {
                    
                    if(cat == 'allergy'){
                      this.allergies.push(response.data);
                      this.allergy = "";
                      bus.$emit('actSummaryAllergies', this.allergies);
                    }
                     if(cat == 'pathological'){
                      this.pathologicals.push(response.data);
                      this.pathological = "";
                      bus.$emit('actSummaryPathologicals', this.pathologicals);
                    }
                     if(cat == 'no_pathological'){
                      this.no_pathologicals.push(response.data);
                      this.no_pathological = "";
                      bus.$emit('actSummaryNoPathologicals', this.no_pathologicals);
                    }
                    if(cat == 'heredo'){
                      this.heredos.push(response.data);
                      this.heredo = "";
                      bus.$emit('actSummaryHeredos', this.heredos);
                    }
                    if(cat == 'gineco'){
                      this.ginecos.push(response.data);
                      this.gineco = "";
                      bus.$emit('actSummaryGinecos', this.ginecos);
                    }
                    
                    
                  }
                  this.loader = false;

              }, (response) => {
                 
                   bus.$emit('alert', 'Error al guardar el Hisotrial', 'danger');
                  this.loader = false;
              });

            

          },
          updateDiagnostics(data){

             this.diagnosticsToday.push(data);
          }
          
          /*updateHistory () {

            var resource = this.$resource('/medic/patients{/id}/history');

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
          }*/
         
     
      },
      components:{
        HistoryItem
      },
      created () {
           console.log('Component ready. history.')

           bus.$on('actHistoryDiagnostics', this.updateDiagnostics);
          
           if(this.history.allergies)
           {
            
                this.allergies = this.history.allergies;
                 
           }
           if(this.history.pathologicals)
           {
            
                this.pathologicals = this.history.pathologicals;
                 
           }
           if(this.history.nopathologicals)
           {
            
                this.no_pathologicals = this.history.nopathologicals;
                 
           }
           if(this.history.heredos)
           {
            
                this.heredos = this.history.heredos;
                 
           }
           if(this.history.ginecos)
           {
            
                this.ginecos = this.history.ginecos;
                 
           }
           if(this.appointments)
           {
               this.diagnostics = this.appointments;
               /*for (var i = 0; i < this.appointments.length; i++) {

                    this.diagnostics = this.appointments[i].diagnostics;
               }*/
               
                 
           }



           
          
      }
}
</script>