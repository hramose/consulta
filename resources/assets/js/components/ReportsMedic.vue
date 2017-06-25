<template>	
  <div>
   <!-- reportes general para el perfil del medico -->
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
                                 
                                  <chartjs-pie :scalesdisplay="false"  :labels="dataLabels"  :datasets="dataSets"></chartjs-pie>
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

         <div v-if="data.length">
           <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">Ventas</h3>
                
              </div>
              <div class="box-body">

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                              <div class=" col-xs-12" >
                                    <!-- small box -->
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
                                    <!-- small box -->
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
         
          
         
        </div>
        

         
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
                               
                                  <img src="/img/muy-malo-off.png" alt="1" title="Muy Malo" v-else>
                              
                              
                                 <img src="/img/malo.png" alt="2" title="Malo" v-if="2 <= dataReviews.rating_service_cache && dataReviews.rating_service_cache < 3">
                               
                                  <img src="/img/malo-off.png" alt="2" title="Malo" v-else>
                               
                                 <img src="/img/regular.png" alt="3" title="regular" v-if="3 <= dataReviews.rating_service_cache && dataReviews.rating_service_cache < 4">
                               
                                  <img src="/img/regular-off.png" alt="3" title="regular" v-else>
                               
                               
                                 <img src="/img/bueno.png" alt="4" title="Bueno" v-if="4 <= dataReviews.rating_service_cache && dataReviews.rating_service_cache < 5">
                                
                                  <img src="/img/bueno-off.png" alt="4" title="Bueno" v-else>
                               
                      
                                 <img src="/img/excelente.png" alt="5" title="Excelente" v-if="5 <= dataReviews.rating_service_cache" >
                               
                                  <img src="/img/excelente-off.png" alt="5" title="Excelente" v-else>
                               
                             
                           

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

          <div class="box box-danger">
              <div class="box-header">
                 <h3 class="box-title">Nivel de satisfacción con el desempeño del médico</h3>
                
              </div>
              <div class="box-body">

                  <div class="row">
                      <div class="col-xs-12 col-sm-6">
                            <div class=" col-xs-12" >
                                <div class="ratings">
                              
                                    <img src="/img/muy-malo.png" alt="1" title="Muy Malo" v-if="1 <= dataReviews.rating_medic_cache && dataReviews.rating_medic_cache < 2">
                                 
                                    <img src="/img/muy-malo-off.png" alt="1" title="Muy Malo" v-else>
                                
                                
                                   <img src="/img/malo.png" alt="2" title="Malo" v-if="2 <= dataReviews.rating_medic_cache && dataReviews.rating_medic_cache < 3">
                                 
                                    <img src="/img/malo-off.png" alt="2" title="Malo" v-else>
                                 
                                   <img src="/img/regular.png" alt="3" title="regular" v-if="3 <= dataReviews.rating_medic_cache && dataReviews.rating_medic_cache < 4">
                                 
                                    <img src="/img/regular-off.png" alt="3" title="regular" v-else>
                                 
                                 
                                   <img src="/img/bueno.png" alt="4" title="Bueno" v-if="4 <= dataReviews.rating_medic_cache && dataReviews.rating_medic_cache < 5">
                                  
                                    <img src="/img/bueno-off.png" alt="4" title="Bueno" v-else>
                                 
                        
                                   <img src="/img/excelente.png" alt="5" title="Excelente" v-if="5 <= dataReviews.rating_medic_cache" >
                                 
                                    <img src="/img/excelente-off.png" alt="5" title="Excelente" v-else>
                                 
                               
                             

                              </div>
                              <div class="ratings-targets">{{ dataReviews.rating_medic_cache }} Puntos</div>
                          </div>
                      </div>
                      <div class="col-xs-12 col-sm-6">
                          <div class=" col-xs-12" >
                                  <!-- small box -->
                                  <div class="small-box bg-green" >
                                    <div class="inner">
                                      <h3>{{ dataReviews.rating_medic_count }}</h3>

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
      props: ['medic'],
      data () {
        return {
          reportTypes:['General','Evaluación de usuario'],
          reviewTypes:['Clínica','Médico'],
          search:{
            type:'General',
            reviewType: 'Médico',
            medic:'',
            clinic:'',
            date1:'',
            date2:''
          },
          
          data:[],
          dataPatients:[],
          dataSales:[],
          dataReviews:[],
          statuses:['Reservadas','Atendidas','No Asistió'],
          statusesColorClass:['bg-aqua','bg-green','bg-yellow'],
          statusesColor:['#00c0ef','#00a65a','#f39c12'],
          dataLabels:[],
          dataSets:[],
          dataValues:[],
          loader: false,
          message:""
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

            //this.search.medic = '';
            this.search.speciality ='';
            this.search.reviewType ='Médico';

        
         

        },
        changeReviews(){

           
          

        },

       
        clearData(){
          
           this.dataSets = [];
           this.dataPoll = [];
           this.data = [];
           this.dataPatients = [];
           this.dataSales = [];
           this.dataReviews = [];
          this.message= "";
        },

        generateReport(){
          
           let queryParam = this.search;
          
            this.clearData();
            
         
            
               if(this.search.type != "Evaluación de usuario" && (this.search.date1 == "" || this.search.date2 == ""))
                {
                  this.message = "Seleccione un rango de fechas!";
                  return
                }

                 this.loader = true;

            this.$http.get('/medic/reports/generate', {params: Object.assign(queryParam, this.data)})
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
               this.dataSales = resp.data.sales
              
               this.getDataForChart();
               this.loader = false;

            });

        }

      },
      created () {
             console.log('Component ready. reports Clinic')

             if(this.medic)
               this.search.medic = this.medic
            
        }
    }
</script>