<template>	
  <div>
    <div class="form-horizontal">
     
      <div class="form-group">
        <label for="office_name" class="col-sm-2 control-label">Buscar</label>
         
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <div class="col-sm-12">
                      <select class="form-control " style="width: 100%;" name="type" v-model="search.type" @change="changeOptions()">
                        <option disabled="disabled"></option>
                         <option v-for="item in reportTypes" v-bind:value="item">{{ item }}</option>
                        
                      </select>
                    </div>
                </div>
                    
                  
              </div>
              <div class="col-xs-12 col-sm-3" v-show="search.type == 'Evaluación de usuario' ">
                <div class="form-group">
                    <div class="col-sm-12">
                      <select class="form-control " style="width: 100%;" name="type" v-model="search.reviewType" @change="changeOptions()">
                        <option disabled="disabled"></option>
                         <option v-for="item in reviewTypes" v-bind:value="item">{{ item }}</option>
                        
                      </select>
                    </div>
                </div>
                    
                  
              </div>
              <div class="col-xs-12 col-sm-3" v-show="search.type != 'Evaluación de usuario' || search.reviewType != 'Clínica' ">
                <div class="form-group">
                    <div class="col-sm-12">
                    <v-select :debounce="250" :on-search="getOptions"  :options="options" placeholder="Buscar..." label="name" :on-change="selectItem" :value.sync="selectedItem" ></v-select>
                    </div>
                </div>
                    
                  
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <div class="col-sm-12">
                      <button @click="generateReport()">Generar</button>
                    </div>
                </div>
                    
                  
              </div>
             
        
        </div>
    </div>
    <div class="row" v-show="data.length">
      <div v-for="statistic in data">
        <h3 class="no-margin">{{ statistic['items'] }}</h3>
             {{ getStatusName(statistic['status']) }}
      </div>
    </div>

  </div>
</template>

<script>
    import vSelect from 'vue-select'


    export default {
      
      data () {
        return {
          reportTypes:['General','Médico','Departamento','Evaluación de usuario'],
          reviewTypes:['Clínica','Médico'],
          search:{
            type:'General',
            reviewType: 'Clínica',
            medic:'',
            speciality:''
          },
          options:[],
          urlOptions:'/clinic/medics/list',
          selectedItem:null,
          data:[],
          statuses:['Reservadas','Atendidas','No Asistió']

        }
      },
      components:{
        vSelect
      },
       methods: {
          getOptions:_.debounce(function(search,loading) {
             loading(true)

          
              let queryParam = {
                ['q']: search
              }
            this.$http.get(this.urlOptions, {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               
               this.options = resp.data
               loading(false)
            })
          

        }, 500),
        getStatusName(status){
          return this.statuses[status];
        },
        changeOptions(){
          if(this.search.type == 'General'){

            this.urlOptions = '/clinic/medics/list';
            this.search.medic = '';
            this.search.speciality ='';
            this.selectedItem = null;
          }
          
          if(this.search.type == 'Médico')
            this.urlOptions = '/clinic/medics/list';

           if(this.search.type == 'Departamento')
            this.urlOptions = '/clinic/specialities/list';

          if(this.search.type == 'Evaluación de usuario'){

            if(this.search.reviewType == 'Médico')
              this.urlOptions = '/clinic/medics/list';

          }

        },

        selectItem(item){
          
          if(this.search.type == 'Médico')
            this.search.medic = item.id
           if(this.search.type == 'Departamento')
            this.search.speciality = item.id
        },

        generateReport(){

           let queryParam = this.search;

            this.$http.get('/clinic/reports/generate', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               //alert('reporte')
               this.data = resp.data
               
            });

        }

      },
      created () {
             console.log('Component ready. reports Clinic')

            
        }
    }
</script>