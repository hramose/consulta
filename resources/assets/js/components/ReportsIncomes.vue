<template>	
  <div>
   <!-- reportes ingresos para el perfil del administrador -->
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
                              <button @click="generateReport()" class="btn btn-success">Generar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
                            </div>
                        </div>
                            
                          
                      </div>
                     
                
                </div>
                <div class="form-group" v-show="message">
                  <div class="col-xs-12">
                    <span class="label label-danger">{{  message }}</span>
                    </div>
                </div>
            </div>
           
          
        </div>
     </div>
      <div v-if="data.general">
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">Periodo: {{ search.date1 }} - {{ search.date2 }}</h3>
                
              </div>
              <div class="box-body">

                    
                        <div class="col-xs-12 ">
                             <div class="table-responsive">
                              <table class="table no-margin">
                                <thead>
                                <tr>
                                  <th>Médico</th>
                                  <th>Consultas Atendidas</th>
                                  <th>Ingresos Recibidos</th>
                                  <th>Pendientes de Pago</th>
                                  <th>Pagos Aplicados</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>General</td>
                                    <td>{{ data.general.appointments }}</td>
                                    <td>₡{{ money(data.general.incomes) }}</td>
                                    <td>
                                      ₡{{  money(parseFloat(data.general.pending) ) }}
                                    </td>
                                    <td>
                                      ₡{{  money(parseFloat(data.general.paid) ) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Especialista</td>
                                    <td>{{ data.specialist.appointments }}</td>
                                    <td>₡{{ money(data.specialist.incomes) }}</td>
                                    <td>
                                      ₡{{  money(parseFloat(data.specialist.pending) ) }}
                                    </td>
                                    <td>
                                      ₡{{  money(parseFloat(data.specialist.paid) ) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Totales</b></td>
                                    <td>{{ parseInt(data.specialist.appointments) +  parseInt(data.general.appointments) }}</b></td>
                                    <td><b>₡{{ money(parseFloat(data.specialist.incomes) + parseFloat(data.general.incomes)) }}</b></td>
                                    <td>
                                      <b>₡{{  money(parseFloat(data.specialist.pending) + parseFloat(data.general.pending) ) }}</b>
                                    </td>
                                    <td>
                                     <b> ₡{{  money(parseFloat(data.specialist.paid) + parseFloat(data.general.paid)) }}</b>
                                    </td>
                                </tr>
                                
                                </tbody>
                              </table>
                            </div>
                        </div>
                        
                  

                
              </div>
          </div>
          <div class="row">
                  <div class="col-xs-12 col-sm-6">
                      <div class="box box-default box-chart">
                          <div class="box-header">
                            <h4>Médicos Generales: Ingresos recibidos vs pendientes</h4>
                          </div>
                          <div class="box-body">
                            <!-- <appointments-chart></appointments-chart> -->
                           
                            <chartjs-pie :scalesdisplay="false"  :labels="dataLabelsGeneral"  :datasets="dataSetsGeneral"  :option="myoption"></chartjs-pie>
                          </div>
                          <!-- /.box-body -->
                    </div>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                      <div class="box box-default box-chart">
                           <div class="box-header">
                           <h4> Médicos Especialistas: Ingresos recibidos vs pendientes</h4>
                          </div>
                          <div class="box-body">
                            <!-- <appointments-chart></appointments-chart> -->
                           
                            <chartjs-pie :scalesdisplay="false"  :labels="dataLabelsSpecialist"  :datasets="dataSetsSpecialist" :option="myoption"></chartjs-pie>
                          </div>
                          <!-- /.box-body -->
                    </div>
                  </div>
            </div>
         
          
         
        </div>

       
          
                 
                
         
     
        
     

        

     

  </div>
</template>

<script>
    //import vSelect from 'vue-select'
    //import AppointmentsChart from './charts/AppointmentsChart.vue'

    export default {
      //props: ['clinic'],
      data () {
        return {
        
        
          search:{
            date1:'',
            date2:''
          },

          data:[],
          dataSetsGeneral:[],
          dataSetsSpecialist:[],
          dataLabelsGeneral:[],
          dataLabelsSpecialist:[],
          loader: false,
          message: "",
          myOption:{} //fix pie chart
          
        }
      },
      // components:{
      //   vSelect
      // },
      computed:{
         

        },
        
    

       methods: {
         money(n, currency) {
                return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
            },
         getDataForChart(){
             let valuesGeneral = [];
             let valuesSpecialist = [];

            this.dataLabelsGeneral[0] = 'Ingresos Recibidos';
            this.dataLabelsGeneral[1] = 'Pendientes de Pago';
            this.dataLabelsSpecialist[0] = 'Ingresos Recibidos';
            this.dataLabelsSpecialist[1] = 'Pendientes de Pago';
            
            valuesGeneral[0] = this.data.general.incomes;
            valuesGeneral[1] = this.data.general.pending;

            valuesSpecialist[0] = this.data.specialist.incomes;
            valuesSpecialist[1] = this.data.specialist.pending;

            this.dataSetsGeneral.push({
                  data: valuesGeneral,
                  backgroundColor: ['#00c0ef','#00a65a'],
                  hoverBackgroundColor: ['#00c0ef','#00a65a']
               });

            this.dataSetsSpecialist.push({
                  data: valuesSpecialist,
                  backgroundColor: ['#00c0ef','#00a65a'],
                  hoverBackgroundColor: ['#00c0ef','#00a65a']
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
         
        


        generateReport(){
         
           let queryParam = this.search;
           
            this.dataSetsGeneral = [];
            this.dataSetsSpecialist = [];
            this.data = [];
            this.message= "";
            
               if(this.search.date1 == "" || this.search.date2 == "")
                {
                  this.message = "Seleccione un rango de fechas!";
                  return
                }

                 this.loader = true;

                this.$http.get('/admin/reports/incomes/generate', {params: Object.assign(queryParam, this.data)})
                .then(resp => {
                   //alert('reporte')
                   console.log(resp.data);
                   this.data = resp.data
                   this.getDataForChart();
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