<template>	
  <div>
    <!-- reportes clinicas para el perfil del administrador -->
    <div class="box">
        <div class="box-header">
            
        </div>
        <div class="box-body">
            <div class="form-horizontal">
             
              <div class="form-group">
                
                 
                    
                     
                      <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <div class="col-sm-12">
                            <v-select :debounce="250" :on-search="getOptions"  :options="options" placeholder="Buscar Clínica..." label="name" :on-change="selectItem" :value.sync="selectedItem" ></v-select>
                            </div>
                        </div>
                            
                          
                      </div>
                      <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <div class="col-sm-12">
                            <v-select :debounce="250" :on-search="getMedics"  :options="medics" placeholder="Buscar Médico..." label="name" :on-change="selectMedic" :value.sync="selectedMedic" ></v-select>
                            </div>
                        </div>
                            
                          
                      </div>
                      <div class="col-xs-12 col-sm-2">
                        <div class="form-group">
                          <div class="col-sm-12">
                            <div class="input-group">
                              <input type="text" class="form-control"  name="date1" id="datepicker1" v-model="search.date1" @blur="onBlurDate1">

                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                       <div class="col-xs-12 col-sm-2">
                          <div class="form-group">
                            <div class="col-sm-12">
                              <div class="input-group">
                                <input type="text" class="form-control"  name="date2" id="datepicker2" v-model="search.date2" @blur="onBlurDate2">

                                <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                      
                     
                
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-sm-2">
                        <div class="form-group">
                            <div class="col-sm-12">
                              <button @click="generateReport()" class="btn btn-success">Generar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
                            </div>
                        </div>
                            
                          
                      </div>
                </div>
            </div>
           
          
        </div>
     </div>
        
        <div v-if="data.appointments">
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">N° de clínicas: {{ data.clinics }} - N° de consultas: {{ totalAppointments }}</h3>
                
              </div>
              <div class="box-body">

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                              <div class=" col-xs-12" v-for="statistic in data.appointments">
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
                                 
                                  <chartjs-pie :scalesdisplay="false"  :labels="dataLabelsAppointments"  :datasets="dataSetsAppointments"></chartjs-pie>
                                </div>
                                <!-- /.box-body -->
                          </div>
                        </div>
                      </div>
                
              </div>
          </div>
         
          
         
        </div>
        <div v-if="!data.appointments">
          <div class="callout callout-info callout-search">
            
            <h4>No hay datos de consultas!</h4>

            <p>No se encontraron estadisticas con esos parametros!</p>
          </div>
        </div>



        

     

  </div>
</template>

<script>
    import vSelect from 'vue-select'
    //import AppointmentsChart from './charts/AppointmentsChart.vue'

    export default {
      //props: ['clinic'],
      data () {
        return {
          
        
          search:{
            medic:'',
            clinic:'',
            date1:'',
            date2:''
          },
          options:[],
          medics:[],
          urlOptions:'/clinics/list',
          urlMedics:'/medics/list',
          selectedItem:null,
          selectedMedic:null,
          data:[],
          statuses:['Reservadas','Atendidas','No Asistió'],
          statusesColorClass:['bg-aqua','bg-green','bg-yellow'],
          statusesColor:['#00c0ef','#00a65a','#f39c12'],
          dataLabelsAppointments:[],
          dataSetsAppointments:[],
          loader:false
          
        }
      },
      components:{
        vSelect
      },
      computed:{
        
        totalAppointments(){
              let total = 0;

              for (var i = 0; i < this.data.appointments.length; i++) {
                total += parseInt(this.data.appointments[i]['items']);
              }
              
              return total;
            
          }

         

        },
        
    

       methods: {
         
          getDataForChartAppointments(){
            let values = [];
            let colors = [];
            for (var i = 0; i < this.data.appointments.length; i++) {
               this.dataLabelsAppointments[i] = this.getStatusName(this.data.appointments[i]['status']) + '%';
               colors[i] = this.getStatusColor(this.data.appointments[i]['status']);
               values[i] = Math.round(( this.data.appointments[i]['items'] * 100 ) / this.totalAppointments);
            }

            this.dataSetsAppointments.push({
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
         getMedics:_.debounce(function(search,loading) {
             loading(true)

            if(this.search.clinic){
                    let queryParam = {
                      ['q']: search,
                      ['clinic']: this.search.clinic
                    }
                  this.$http.get(this.urlMedics, {params: Object.assign(queryParam, this.data)})
                  .then(resp => {
                     
                     this.medics = resp.data
                     loading(false)
                  })
                }else{
                   loading(false)
                }
          

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
        
        selectItem(item){
        
          if(item){
            
            this.selectedItem = item;
            this.search.clinic = item.id;
          }else{
            this.selectedItem = null;
            this.search.clinic = '';
          }
        },
        selectMedic(item){
          
          if(item){
            
          
              this.search.medic = item.id
         
           
          }else{
      
            this.search.medic = '';
          }
        },

        generateReport(){
            this.loader = true;
           let queryParam = this.search;
           
            
            this.dataSetsAppointments = [];
            this.data = [];
            
            if(!this.search.clinic) return

             

                this.$http.get('/admin/reports/clinics/generate', {params: Object.assign(queryParam, this.data)})
                .then(resp => {
                   //alert('reporte')
                   console.log(resp.data);
                   this.data = resp.data
                  
                   this.getDataForChartAppointments();
                   this.loader = false;
                });



              



        }

      },
      created () {
             console.log('Component ready. reports Clinic')

             /*if(this.clinic)
               this.search.clinic = this.clinic*/
            
        }
    }
</script>