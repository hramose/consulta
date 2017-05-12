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
        
        <div v-if="data.patients">
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">N° de Pacientes: {{ totalPatients }}</h3>
                
              </div>
              <div class="box-body">

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                              <div class=" col-xs-12" v-for="statistic in data.patients">
                                    <!-- small box -->
                                    <div class="small-box " :class="getProvinceColorClass(statistic['province']) " >
                                      <div class="inner">
                                        <h3>{{ statistic['items'] }}</h3>

                                        <p>{{ statistic['province'] }}</p>
                                      </div>
                                      <div class="icon">
                                        <i class="fa fa-users"></i>
                                      </div>
                                      <div class="small-box-footer"></div>
                         
                                    </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="box box-default box-chart">
                                
                                <div class="box-body">
                                  <!-- <appointments-chart></appointments-chart> -->
                                 
                                  <chartjs-pie :scalesdisplay="false"  :labels="dataLabelsPatients"  :datasets="dataSetsPatients"></chartjs-pie>
                                </div>
                                <!-- /.box-body -->
                          </div>
                        </div>
                      </div>
                
              </div>
          </div>
         
          
         
        </div>
        <div v-if="!data.patients">
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
            date1:'',
            date2:''
          },
          options:[],
          urlOptions:'/patients/list',
          selectedItem:null,
          data:[],
          dataLabelsPatients:[],
          dataSetsPatients:[]
          
        }
      },
      components:{
        vSelect
      },
      computed:{
        
        totalPatients(){
              let total = 0;

              for (var i = 0; i < this.data.patients.length; i++) {
                total += parseInt(this.data.patients[i]['items']);
              }
              
              return total;
            
          }

         

        },
        
    

       methods: {
         
          getDataForChartPatients(){
            let values = [];
            let colors = [];
            for (var i = 0; i < this.data.patients.length; i++) {
               this.dataLabelsPatients[i] = this.data.patients[i]['province'] + '%';
               colors[i] = this.getProvinceColor(this.data.patients[i]['province']);
               values[i] = Math.round(( this.data.patients[i]['items'] * 100 ) / this.totalPatients);
            }

            this.dataSetsPatients.push({
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
        
        getProvinceColorClass(province){
          var colorClass = 'bg-red'
     

          if(province == 'Guanacaste')
            colorClass = 'bg-aqua'

           if(province == 'Alajuela')
            colorClass = 'bg-green'

           if(province == 'San Jose')
            colorClass = 'bg-yellow'

           if(province == 'Limón' || province == 'Limon')
            colorClass = 'bg-light-blue'

           if(province == 'Heredia')
            colorClass = 'bg-purple'

           if(province == 'Puntarenas')
            colorClass = 'bg-blue'

           if(province == 'Cartago')
            colorClass = 'bg-red'

         return colorClass;
        },
       
        getProvinceColor(province){
           var colorClass = 'bg-red'
     

          if(province == 'Guanacaste')
            colorClass = '#00c0ef'

           if(province == 'Alajuela')
            colorClass = '#00a65a'

           if(province == 'San Jose')
            colorClass = '#f39c12'

           if(province == 'Limón' || province == 'Limon')
            colorClass = '#3c8dbc'

           if(province == 'Heredia')
            colorClass = '#605ca8'

           if(province == 'Puntarenas')
            colorClass = '#0073b7'

           if(province == 'Cartago')
            colorClass = '#dd4b39'

         return colorClass;
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
       
        generateReport(){

           let queryParam = this.search;
           
            
            this.dataSetsPatients = [];
            this.data = [];
            
           


                this.$http.get('/admin/reports/patients/generate', {params: Object.assign(queryParam, this.data)})
                .then(resp => {
                   //alert('reporte')
                   console.log(resp.data);
                   this.data = resp.data
                  
                   this.getDataForChartPatients();
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