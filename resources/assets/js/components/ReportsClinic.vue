<template>	
  <div>
    <div class="form-horizontal">
     
      <div class="form-group">
        
         
              <div class="col-xs-12 col-sm-2">
                <div class="form-group">
                    <div class="col-sm-12">
                      <select class="form-control " style="width: 100%;" name="type" v-model="search.type" @change="changeTypes()">
                        <option disabled="disabled"></option>
                         <option v-for="item in reportTypes" v-bind:value="item">{{ item }}</option>
                        
                      </select>
                    </div>
                </div>
                    
                  
              </div>
              <div class="col-xs-12 col-sm-2" v-show="search.type == 'Evaluación de usuario' ">
                <div class="form-group">
                    <div class="col-sm-12">
                      <select class="form-control " style="width: 100%;" name="type" v-model="search.reviewType" @change="changeReviews()">
                        <option disabled="disabled"></option>
                         <option v-for="item in reviewTypes" v-bind:value="item">{{ item }}</option>
                        
                      </select>
                    </div>
                </div>
                    
                  
              </div>
              <div class="col-xs-12 col-sm-4" v-show="(search.type != 'General' && search.type != 'Evaluación de usuario') || search.reviewType != 'Clínica' ">
                <div class="form-group">
                    <div class="col-sm-12">
                    <v-select :debounce="250" :on-search="getOptions"  :options="options" placeholder="Buscar..." label="name" :on-change="selectItem" :value.sync="selectedItem" ></v-select>
                    </div>
                </div>
                    
                  
              </div>
              <div class="col-xs-12 col-sm-2">
                <div class="form-group">
                    <div class="col-sm-12">
                      <button @click="generateReport()" class="btn btn-success">Generar</button>
                    </div>
                </div>
                    
                  
              </div>
             
        
        </div>
    </div>
    <div class="content">
    <div class="row" v-show="data.length">
      <div class="col-lg-4 col-sm-6 col-xs-12" v-for="statistic in data">
              <!-- small box -->
              <div class="small-box " :class=" getStatusColor(statistic['status']) ">
                <div class="inner">
                  <h3>{{ statistic['items'] }}</h3>

                  <p>{{ getStatusName(statistic['status']) }}</p>
                </div>
                <div class="icon">
                  <i class="fa fa-edit"></i>
                </div>
                <div class="small-box-footer"></div>
   
              </div>
      </div>
      
    </div>
    <div class="row" v-show="data.length">
      <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
              </div>
            </div>
            <div class="box-body">
              <canvas id="pieChart" style="height: 209px; width: 419px;" height="209" width="419"></canvas>
            </div>
            <!-- /.box-body -->
          </div>
      
    </div>
    </div>

  </div>
</template>

<script>
    import vSelect from 'vue-select'


    export default {
      props: ['clinic'],
      data () {
        return {
          reportTypes:['General','Médico','Departamento','Evaluación de usuario'],
          reviewTypes:['Clínica','Médico'],
          search:{
            type:'General',
            reviewType: 'Clínica',
            medic:'',
            speciality:'',
            clinic:''
          },
          options:[],
          urlOptions:'/clinic/medics/list',
          selectedItem:null,
          data:[],
          statuses:['Reservadas','Atendidas','No Asistió'],
          statusesColor:['bg-aqua','bg-green','bg-yellow']

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
        getStatusColor(status){
          return this.statusesColor[status];
        },
        changeTypes(){

            this.search.medic = '';
            this.search.speciality ='';
            this.search.reviewType ='Clínica';

          if(this.search.type == 'General')
            this.urlOptions = '/clinic/medics/list';
            
          if(this.search.type == 'Médico')
            this.urlOptions = '/clinic/medics/list';

           if(this.search.type == 'Departamento')
            this.urlOptions = '/clinic/specialities/list';

         

          this.selectedItem = null;
          this.options = [];

        },
        changeReviews(){

           
            if(this.search.reviewType == 'Médico')
              this.urlOptions = '/clinic/medics/list';


        },

        selectItem(item){
          
          if(item){
            
            if(this.search.type == 'Médico')
              this.search.medic = item.id
             if(this.search.type == 'Departamento')
              this.search.speciality = item.id
            
            this.selectedItem = item;
           
          }
        },

        generateReport(){

           let queryParam = this.search;
            
            if(this.search.type == "Médico" && !this.search.medic) return

            if(this.search.type == "Departamento" && !this.search.speciality) return

            this.$http.get('/clinic/reports/generate', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               //alert('reporte')
               this.data = resp.data
               
            });

        }

      },
      created () {
             console.log('Component ready. reports Clinic')

             if(this.clinic)
               this.search.clinic = this.clinic
            
        }
    }
</script>