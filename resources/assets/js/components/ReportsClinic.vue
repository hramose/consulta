<template>	
  <div>
  <!-- reportes general para el perfil del clinica -->
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
                    
                      <div class="col-xs-12 col-sm-4" v-show="(search.type != 'General' && search.type != 'Evaluación de usuario') || search.reviewType != 'Clínica' ">
                        <div class="form-group">
                            <div class="col-sm-12">
                            <v-select :debounce="250" :on-search="getOptions"  :options="options" placeholder="Buscar..." label="name" :on-change="selectItem" :value.sync="selectedItem" ></v-select>
                            </div>
                        </div>
                            
                          
                      </div>
                      <div class="col-xs-12 col-sm-2" v-show="search.type != 'Evaluación de usuario'">
                          <div class="input-group">
                            <input type="text" class="form-control"  name="date1" id="datepicker1" v-model="search.date1" @blur="onBlurDate1" >

                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                            </div>
                          </div>
                      </div>
                       <div class="col-xs-12 col-sm-2" v-show="search.type != 'Evaluación de usuario'">
                          <div class="input-group">
                            <input type="text" class="form-control"  name="date2" id="datepicker2" v-model="search.date2" @blur="onBlurDate2" >

                            <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
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
     <div v-if="dataIncomes.individualByAppointmentAttended">
           <div class="box box-success">
              <div class="box-header">
                 <h3 class="box-title">Comision por Cita atendida - Periodo: {{ search.date1 }} - {{ search.date2 }}</h3>
                 <span class="pull-right"><b class="label label-success">Total Comision por citas: ${{ money(parseFloat(dataIncomes.individualByAppointmentAttended.totalAttended + dataIncomes.individualByAppointmentAttended.totalPending)) }}</b>  </span>
                  <!-- <b class="label label-danger">Total Pendiente:  ${{ money(parseFloat(dataIncomes.individualByAppointmentAttended.totalPending)) }}</b> -->
              </div>
              <div class="box-body">

                    
                        <div class="col-xs-12 ">
                             <div class="table-responsive">
                              <table class="table no-margin">
                                <thead>
                                <tr>
                                  <th>Médico</th>
                                  <th>Consultas Atendidas</th>
                                  <th>Monto</th>
                                  <!-- <th>Consultas pedientes</th>
                                  <th>Monto</th> -->
                                  
                                  
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="medic in dataIncomes.individualByAppointmentAttended.medics">
                                    <td>{{ medic.name }}</td>
                                    <td>{{ medic.attented + medic.pending  }}</td>
                                    <td>${{ money(medic.attented_amount + medic.pending_amount) }}</td>
                                    <!-- <td>{{ medic.pending }}</td>
                                    <td>${{ money(medic.pending_amount) }}</td> -->
                                    
                                   
                                </tr>
                               
                                <tr>
                                    <!-- <td><b>Totales</b></td>
                                    <td>{{ dataInvoices.totalAppointments }}</b></td>
                                    <td><b>₡{{ money(parseFloat(dataInvoices.totalInvoices)) }}</b></td>
                                    <td>
                                      <b>₡{{ money(dataInvoices.totalCommission) }}</b>
                                    </td> -->
                                    
                                </tr>
                                
                                </tbody>
                              </table>
                            </div>
                        </div>
                        
                  

                
                </div>
            </div>
          </div>
          <div v-if="dataIncomes.individualByInvoiceBilled">
           <div class="box box-success">
              <div class="box-header">
                 <h3 class="box-title">Comision por Cita Facturada - Periodo: {{ search.date1 }} - {{ search.date2 }}</h3>
                 <span class="pull-right"><b class="label label-success">Total Comision por facturas: ${{ money(parseFloat(dataIncomes.individualByInvoiceBilled.totalBilledCommission)) }}</b>  </span>
                  <!-- <b class="label label-danger">Total Pendiente:  ${{ money(parseFloat(dataIncomes.individualByAppointmentAttended.totalPending)) }}</b> -->
              </div>
              <div class="box-body">

                    
                        <div class="col-xs-12 ">
                             <div class="table-responsive">
                              <table class="table no-margin">
                                <thead>
                                <tr>
                                  <th>Médico</th>
                                  <th>Facturas</th>
                                  <th>Monto Facturado</th>
                                  <th>Comision</th>
                                  <th>Monto Comision</th>
                                  
                                  
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="medic in dataIncomes.individualByInvoiceBilled.medics">
                                    <td>{{ medic.name }}</td>
                                    <td>{{ medic.billed  }}</td>
                                    <td>${{ money(medic.billed_amount) }}</td>
                                    <td>{{ medic.commission }}</td>
                                    <td>${{ money(medic.billed_commission_amount) }}</td>
                                    
                                   
                                </tr>
                               
                                <tr>
                                    <!-- <td><b>Totales</b></td>
                                    <td>{{ dataInvoices.totalAppointments }}</b></td>
                                    <td><b>₡{{ money(parseFloat(dataInvoices.totalInvoices)) }}</b></td>
                                    <td>
                                      <b>₡{{ money(dataInvoices.totalCommission) }}</b>
                                    </td> -->
                                    
                                </tr>
                                
                                </tbody>
                              </table>
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
                                 
                                  <chartjs-pie :scalesdisplay="false"  :labels="dataLabels"  :datasets="dataSets" :option="myOption"></chartjs-pie>
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

         <!-- <div v-if="data.length">
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">Ventas</h3>
                
              </div>
              <div class="box-body">

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                              <div class=" col-xs-12" >
                                   
                                    <div class="small-box bg-aqua" >
                                      <div class="inner">
                                        <h3>₡{{ dataSales.total }}</h3>

                                        <p>Facturas: {{ dataSales.invoices}}</p>
                                      </div>
                                      <div class="icon">
                                        <i class="fa fa-money"></i>
                                      </div>
                                      <div class="small-box-footer"></div>
                         
                                    </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class=" col-xs-12" >
                                   
                                    <div class="small-box bg-green" >
                                      <div class="inner">
                                        <h3>{{ dataPatients }}</h3>

                                        <p>Paciente(s) atendidos</p>
                                      </div>
                                      <div class="icon">
                                        <i class="fa fa-user"></i>
                                      </div>
                                      <div class="small-box-footer"></div>
                         
                                    </div>
                            </div> 
                        </div>
                      </div>
                
              </div>
          </div>
         
          
         
        </div> -->
        

         
      <div v-if="dataReviews.rating_service_cache && this.search.type == 'Evaluación de usuario'" >
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">Nivel de satisfación del  servicio recibido</h3>
                
              </div>
              <div class="box-body">

                  <div class="row">
                    <div class="col-xs-12 col-sm-6">
                          <div class=" col-xs-12" >
                              <div class="ratings">
                            
                                  <img src="/img/muy-malo.png" alt="1" title="Muy Malo" v-if="1 <= dataReviews.rating_service_cache && dataReviews.rating_service_cache < 2">
                               
                                  <img src="/img/muy-malo-off.png" class="evaluation-off" alt="1" title="Muy Malo" v-else>
                              
                              
                                 <img src="/img/malo.png" alt="2" title="Malo" v-if="2 <= dataReviews.rating_service_cache && dataReviews.rating_service_cache < 3">
                               
                                  <img src="/img/malo-off.png" class="evaluation-off" alt="2" title="Malo" v-else>
                               
                                 <img src="/img/regular.png" alt="3" title="regular" v-if="3 <= dataReviews.rating_service_cache && dataReviews.rating_service_cache < 4">
                               
                                  <img src="/img/regular-off.png" class="evaluation-off" alt="3" title="regular" v-else>
                               
                               
                                 <img src="/img/bueno.png" alt="4" title="Bueno" v-if="4 <= dataReviews.rating_service_cache && dataReviews.rating_service_cache < 5">
                                
                                  <img src="/img/bueno-off.png" class="evaluation-off" alt="4" title="Bueno" v-else>
                               
                      
                                 <img src="/img/excelente.png" alt="5" title="Excelente" v-if="5 <= dataReviews.rating_service_cache" >
                               
                                  <img src="/img/excelente-off.png" class="evaluation-off" alt="5" title="Excelente" v-else>
                               
                             
                           

                            </div>
                            <div class="ratings-targets">{{ dataReviews.rating_service_cache }} Puntos</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class=" col-xs-12" >
                                <!-- small box -->
                                <div class="small-box bg-green" >
                                  <div class="inner">
                                    <h3>{{ dataReviews.rating_service_count }}</h3>

                                    <p>Comentarios</p>
                                  </div>
                                  <div class="icon">
                                    <i class="fa fa-comments"></i>
                                  </div>
                                  <div class="small-box-footer"></div>
                     
                                </div>
                        </div> 
                    </div>
                  </div>
            
              </div>
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
          reportTypes:['General','Médico','Evaluación de usuario'],
          reviewTypes:['Clínica','Médico'],
          search:{
            type:'General',
            reviewType: 'Clínica',
            medic:'',
            clinic:'',
            date1:'',
            date2:''
          },
          options:[],
          myOption:{}, //fix pie chart
          urlOptions:'/clinic/medics/list',
          selectedItem:null,
          data:[],
          dataPatients:[],
          //dataSales:[],
          dataReviews:[],
          dataInvoices:[],
          dataIncomes:[],
          statuses:['Reservadas','Atendidas','No Asistió'],
          statusesColorClass:['bg-aqua','bg-green','bg-yellow'],
          statusesColor:['#00c0ef','#00a65a','#f39c12'],
          dataLabels:[],
          dataSets:[],
          dataValues:[],
          dataLabelsIncomes:[],
          dataSetsIncomes:[],
          dataValuesIncomes:[],
          loader: false,
          message:''
        }
      },
      components:{
        vSelect
      },
      computed:{

         totalAppointments(){
              let total = 0;

              for (var i = 0; i < this.data.length; i++) {
                total += parseInt(this.data[i]['items']);
              }
              
              return total;
            
          }
        },

       methods: {
         money(n, currency) {
                if(n)
                    return n.toLocaleString();//toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
                
                return 0;
            },
        totalInvoices(invoices){
              let total = 0;

              for (var i = 0; i < invoices.length; i++) {
                total += parseFloat(invoices[i].total);
              }
              
              return total;
            
          
        },
         totalCommission(invoices,commission){
              let total = 0;

              for (var i = 0; i < invoices.length; i++) {
                total += parseFloat(invoices[i].total) * parseFloat((commission)/100);
              }
              
              return total;
            
          
        },
       
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

            /* let valuesIncomes = [];
           

            this.dataLabelsIncomes[0] = 'Ingresos Recibidos';
            this.dataLabelsIncomes[1] = 'Pendientes de Pago';
         
            valuesIncomes[0] = this.dataIncomes.totalIncomes;
            valuesIncomes[1] = this.dataIncomes.totalPending;

  

            this.dataSetsIncomes.push({
                  data: valuesIncomes,
                  backgroundColor: ['#00c0ef','#00a65a'],
                  hoverBackgroundColor: ['#00c0ef','#00a65a']
               });
*/
        
            
           
          },
          /*getDataPollForChart(){
            let values = [];
            let colors = [];
            for (var i = 0; i < this.dataPoll.length; i++) {
               this.dataLabels[i] = this.dataPoll[i]['answers'];
               //colors[i] = this.getStatusColor(this.data[i]['status']);
               values[i] = this.dataPoll[i]['rate']*100 / this.dataPoll[i]['totalAnswers']; // (ans.rate*100/statistic['totalAnswers'])
            }

            this.dataSets.push({
                  data: values
                  //backgroundColor: colors,
                  //hoverBackgroundColor: colors
               });
            
           
          },*/
        
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
            
            if(this.search.type == 'Médico' || this.search.reviewType == 'Médico' )
              this.search.medic = item.id
             if(this.search.type == 'Departamento')
              this.search.speciality = item.id
            
            this.selectedItem = item;
           
          }
        },
        clearData(){
          
           this.dataSets = [];
           this.dataPoll = [];
           this.data = [];
           this.dataPatients = [];
           //this.dataSales = [];
           this.dataReviews = [];
           this.dataIncomes = [];
           this.dataSetsIncomes =[];
           this.message = "";
        },

        generateReport(){
         
           let queryParam = this.search;
          
            this.clearData();
            
            if(this.search.type == "Médico" && !this.search.medic)
            {
              this.message = "Seleccione un Médico!";
              return
            }

            if(this.search.type == "Departamento" && !this.search.speciality) 
            {
              this.message = "Seleccione una Especialidad!";
              return
            }

            if(this.search.type != "Evaluación de usuario" && (this.search.date1 == "" || this.search.date2 == "")) 
            {  
              this.message = "Seleccione un rango de fechas!";
              return
            }

            this.loader = true;

            this.$http.get('/clinic/reports/generate', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               //alert('reporte')
              // this.dataPoll = resp.data;
              if(this.search.type == "Evaluación de usuario")
              {
                this.dataReviews = resp.data;
                this.loader = false;
                return;
              }

               this.data = resp.data.appointments
               this.dataPatients = resp.data.patients
               //this.dataSales = resp.data.sales

              
              //  this.dataInvoices =  resp.data.invoices
                
             
               
              
               this.getDataForChart();

               this.loader = false;

            });

            this.$http.get('/clinic/reports/incomes/generate', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               //alert('reporte')

              
                this.dataIncomes =  resp.data
                
             
               
          

               this.loader = false;

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