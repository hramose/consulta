<template>	
  <div>
    <div class="box">
        <div class="box-header">
            
        </div>
        <div class="box-body">
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
                          <div class="input-group">
                            <input type="text" class="form-control"  name="date1" id="datepicker1" v-model="search.date1" @blur="onBlurDate1">

                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                          </div>
                      </div>
                       <div class="col-xs-12 col-sm-2">
                          <div class="input-group">
                            <input type="text" class="form-control"  name="date2" id="datepicker2" v-model="search.date2" @blur="onBlurDate2">

                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
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
           
          
        </div>
     </div>
      <div v-if="data.length">
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">N° de consultas: {{ totalAppointments }}</h3>
                
              </div>
              <div class="box-body">

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                              <div class=" col-xs-12" v-for="statistic in data">
                                    <!-- small box -->
                                    <div class="small-box " :class="getStatusColorClass(statistic['status']) ">
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
                        <div class="col-xs-12 col-sm-6">
                            <div class="box box-default box-chart">
                                
                                <div class="box-body">
                                  <!-- <appointments-chart></appointments-chart> -->
                                 
                                  <chartjs-pie :scalesdisplay="false" :datalabel="sss" :labels="dataLabels"  :datasets="dataSets"></chartjs-pie>
                                </div>
                                <!-- /.box-body -->
                          </div>
                        </div>
                      </div>
                
              </div>
          </div>
         
          
         
        </div>
        <div v-else>
          <div class="callout callout-info callout-search">
            
            <h4>No hay datos !</h4>

            <p>No se encontraron estadisticas con esos parametros!</p>
          </div>
        </div>
    

  </div>
</template>

<script>
    import vSelect from 'vue-select'
    //import AppointmentsChart from './charts/AppointmentsChart.vue'

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
            clinic:'',
            date1:'',
            date2:''
          },
          options:[],
          urlOptions:'/clinic/medics/list',
          selectedItem:null,
          data:[],
          statuses:['Reservadas','Atendidas','No Asistió'],
          statusesColorClass:['bg-aqua','bg-green','bg-yellow'],
          statusesColor:['#00c0ef','#00a65a','#f39c12'],
          dataLabels:[],
          dataSets:[],
          dataValues:[]
        }
      },
      components:{
        vSelect
      },
      computed:{
         totalAppointments(){
              let total = 0;

              for (var i = 0; i < this.data.length; i++) {
                total += this.data[i]['items'];
              }
              
              return total;
            
          }
        },

       methods: {
         getDataForChart(){
            let values = [];
            let colors = [];
            for (var i = 0; i < this.data.length; i++) {
               this.dataLabels[i] = this.getStatusName(this.data[i]['status']);
               colors[i] = this.getStatusColor(this.data[i]['status']);
               values[i] = this.data[i]['items'];
            }

            this.dataSets.push({
                  data: values,
                  backgroundColor: colors,
                  hoverBackgroundColor: colors
               });
            
           
          },
        
          onBlurDate1(e){
            const value = e.target.value;

            this.search.date1 = value;
            
          },
          onBlurDate2(e){
            const value = e.target.value;

            this.search.date2 = value;
            
          },
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
        getStatusColorClass(status){
          return this.statusesColorClass[status];
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
               this.getDataForChart();
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