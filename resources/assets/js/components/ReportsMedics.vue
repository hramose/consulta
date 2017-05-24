<template>	
  <div>
   <!-- reportes medicos para el perfil del administrador -->
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
                     
                      <div class="col-xs-12 col-sm-4" v-show="search.type != 'General' ">
                        <div class="form-group">
                            <div class="col-sm-12">
                            <v-select :debounce="250" :on-search="getOptions"  :options="options" placeholder="Buscar..." label="name" :on-change="selectItem" :value.sync="selectedItem" ></v-select>
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
      <div v-if="data.medics">
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">N° de médicos registrados: {{ totalMedics }}</h3>
                
              </div>
              <div class="box-body">

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                              <div class=" col-xs-12" v-for="statistic in data.medics">
                                    <!-- small box -->
                                    <div class="small-box " :class="getStatusMedicsColorClass(statistic['active']) ">
                                      <div class="inner">
                                        <h3>{{ statistic['items'] }}</h3>

                                        <p>{{ getStatusMedicsName(statistic['active']) }}</p>
                                      </div>
                                      <div class="icon">
                                        <i class="fa fa-user-md"></i>
                                      </div>
                                      <div class="small-box-footer"></div>
                         
                                    </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="box box-default box-chart">
                                
                                <div class="box-body">
                                  <!-- <appointments-chart></appointments-chart> -->
                                 
                                  <chartjs-pie :scalesdisplay="false"  :labels="dataLabelsMedics"  :datasets="dataSetsMedics"></chartjs-pie>
                                </div>
                                <!-- /.box-body -->
                          </div>
                        </div>
                      </div>
                
              </div>
          </div>
         
          
         
        </div>
        
        <div v-if="data.appointments">
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">N° de consultas: {{ totalAppointments }}</h3>
                
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
          reportTypes:['General','Médico'],
        
          search:{
            type:'General',
            medic:'',
            date1:'',
            date2:''
          },
          options:[],
          urlOptions:'/medics/list',
          selectedItem:null,
          data:[],
          dataByMedic:[],
          statuses:['Reservadas','Atendidas','No Asistió'],
          statusesMedics:['Inactivos','Activos'],
          statusesColorClass:['bg-aqua','bg-green','bg-yellow'],
          statusesMedicsColorClass:['bg-red','bg-green'],
          statusesColor:['#00c0ef','#00a65a','#f39c12'],
          statusesMedicsColor:['#dd4b39','#00a65a'],
          dataLabelsMedics:[],
          dataLabelsAppointments:[],
          dataSetsMedics:[],
          dataSetsAppointments:[]
          
        }
      },
      components:{
        vSelect
      },
      computed:{
         totalMedics(){
              let total = 0;

              for (var i = 0; i < this.data.medics.length; i++) {
                total += parseInt(this.data.medics[i]['items']);
              }
              
              return total;
            
          },
        totalAppointments(){
              let total = 0;

              for (var i = 0; i < this.data.appointments.length; i++) {
                total += parseInt(this.data.appointments[i]['items']);
              }
              
              return total;
            
          }

         

        },
        
    

       methods: {
         getDataForChartMedics(){
            let values = [];
            let colors = [];
            for (var i = 0; i < this.data.medics.length; i++) {
               this.dataLabelsMedics[i] = this.getStatusMedicsName(this.data.medics[i]['active']) + '%';
               colors[i] = this.getStatusMedicsColor(this.data.medics[i]['active']);

               values[i] = Math.round(( this.data.medics[i]['items'] * 100 ) / this.totalMedics);
            }

            this.dataSetsMedics.push({
                  data: values,
                  backgroundColor: colors,
                  hoverBackgroundColor: colors
               });
            
           
          },
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
        getStatusName(status){
          return this.statuses[status];
        },
        getStatusMedicsName(status){
            return this.statusesMedics[status];
        },
        getStatusColorClass(status){
          return this.statusesColorClass[status];
        },
        getStatusMedicsColorClass(status){
        
          return this.statusesMedicsColorClass[status];
        },
        getStatusColor(status){
          return this.statusesColor[status];
        },
         getStatusMedicsColor(status){
          return this.statusesMedicsColor[status];
        },
        changeTypes(){

            this.search.medic = '';
            

          if(this.search.type == 'General')
            this.urlOptions = '/medics/list';
            
          if(this.search.type == 'Médico')
            this.urlOptions = '/medics/list';
         

          this.selectedItem = null;
          this.options = [];

        },
        

        selectItem(item){
          
          if(item){
            
            if(this.search.type == 'Médico')
              this.search.medic = item.id
           
            this.selectedItem = item;
           
          }
        },

        generateReport(){

           let queryParam = this.search;
           
            this.dataSetsMedics = [];
            this.dataSetsAppointments = [];
            this.data = [];
            
            if(this.search.type == "Médico" && !this.search.medic) return

             if(this.search.type == "Médico"){

                  this.$http.get('/admin/reports/medics/'+this.search.medic+'/generate', {params: Object.assign(queryParam, this.data)})
                  .then(resp => {
                     //alert('reporte')
                     console.log(resp.data);
                     this.data = resp.data
                     //this.getDataForChartMedics();
                     this.getDataForChartAppointments();
                  });
              }else{

                this.$http.get('/admin/reports/medics/generate', {params: Object.assign(queryParam, this.data)})
                .then(resp => {
                   //alert('reporte')
                   console.log(resp.data);
                   this.data = resp.data
                   this.getDataForChartMedics();
                   this.getDataForChartAppointments();
                });



              }



        }

      },
      created () {
             console.log('Component ready. reports Clinic')

             /*if(this.clinic)
               this.search.clinic = this.clinic*/
            
        }
    }
</script>