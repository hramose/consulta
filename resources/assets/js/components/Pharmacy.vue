<template>	
	<div class="form-horizontal">
     
      <div class="newform">

            <div class="form-group">
              <label for="pharmacy_name" class="col-sm-2 control-label">Nombre</label>
              <div class="col-sm-10">

                 <input type="text" class="form-control" name="name" placeholder="Nombre del consultorio" v-model="pharmacy.name" >
                <form-error v-if="errors.name" :errors="errors" style="float:right;">
                    {{ errors.name[0] }}
                </form-error> 
              </div>
            </div>
           
          <div class="form-group">
            <label for="pharmacy_address" class="col-sm-2 control-label">Dirección</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="address" placeholder="Dirección"  v-model="pharmacy.address" >
              <form-error v-if="errors.address" :errors="errors" style="float:right;">
                  {{ errors.address[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="pharmacy_province" class="col-sm-2 control-label">Provincia</label>

            <div class="col-sm-10">
              <select class="form-control " style="width: 100%;" name="province" placeholder="-- Selecciona provincia --"  v-model="pharmacy.province" v-on:change="onChangeProvince" >
                <option disabled="disabled"></option>
                <option v-for="item in provincias" v-bind:value="item.title">{{ item.title }}</option>
                
              </select>
              <form-error v-if="errors.province" :errors="errors" style="float:right;">
                  {{ errors.province[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="pharmacy_canton" class="col-sm-2 control-label">Canton</label>

            <div class="col-sm-10">
              <select class="form-control " style="width: 100%;" name="canton" placeholder="-- Selecciona canton --"  v-model="pharmacy.canton" v-on:change="onChangeCanton" >
                <option disabled="disabled"></option>
                <option v-for="item in cantones" v-bind:value="item.title">{{ item.title }}</option>
                
              </select>
              <form-error v-if="errors.canton" :errors="errors" style="float:right;">
                  {{ errors.canton[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="pharmacy_district" class="col-sm-2 control-label">Distrito</label>

            <div class="col-sm-10">
              <select class="form-control " style="width: 100%;" name="district" placeholder="-- Selecciona distrito --"  v-model="pharmacy.district" >
                <option disabled="disabled"></option>
                <option v-for="item in distritos" v-bind:value="item">{{ item }}</option>
                
              </select>
              <form-error v-if="errors.district" :errors="errors" style="float:right;">
                  {{ errors.district[0] }}
              </form-error>
            </div>
          </div>
          
          <div class="form-group">
            <label for="pharmacy_phone" class="col-sm-2 control-label">Teléfono</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="phone" placeholder="Teléfono" v-model="pharmacy.phone">
              <form-error v-if="errors.phone" :errors="errors" style="float:right;">
                  {{ errors.phone[0] }}
              </form-error>
            </div>
          </div>
            <div class="form-group" >
            <label for="pharmacy_ide" class="col-sm-2 control-label">Cédula</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="ide" placeholder="Cédula Jurídica"  v-model="pharmacy.ide">
              <form-error v-if="errors.ide" :errors="errors" style="float:right;">
                  {{ errors.ide[0] }}
              </form-error>
            </div>
          </div>
            <div class="form-group">
              <label for="ide_name" class="col-sm-2 control-label">Nombre Jurídico</label>
              <div class="col-sm-10">

                 <input type="text" class="form-control" name="ide_name" placeholder="Nombre Jurídico" v-model="pharmacy.ide_name">
                <form-error v-if="errors.ide_name" :errors="errors" style="float:right;">
                    {{ errors.ide_name[0] }}
                </form-error> 
              </div>
            </div>
        
        
           <div class="form-group">
            <label for="lat" class="col-sm-2 control-label">Coordenadas (Para Google Maps y Waze)</label>
                
                                                      
            
                 <div class="col-sm-3">
                  <div class="form-group">
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon">lat:</span>
                        <input type="text" class="form-control" name="lat" placeholder="10.637875" v-model="pharmacy.lat" >
                      </div>
                    </div>
                  </div>
                  
                   
                </div>
                <div class="col-sm-3">
                   <div class="form-group">
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon">lon:</span>
                        <input type="text" class="form-control" name="lon" placeholder="-85.434431" v-model="pharmacy.lon" >
                      </div>
                    </div>
                  </div>
                   
                </div>
               
                <div class="col-sm-3">
              
                  <div class="form-group">

                     
                    <div class="col-sm-6">
                    
                      
                       <button type="button" class="btn btn-default btn-geo" @click="getGeolocation" ><i class="fa fa-"></i>Tu ubicación Actual</button>
                     
                    </div>
             
                    
                  </div>

                  

                  <!-- Modal -->
                  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Ejemplo de Coordenadas</h4>
                        </div>
                        <div class="modal-body">
                          <img src="/img/img-mapa-coordenadas.png" alt="Coordenadas Google Maps" style="width: 100%;" />
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Modal -->
                  <div class="modal fade" id="modalPharmacyNotification" tabindex="-1" role="dialog" aria-labelledby="modalPharmacyNotificationLabel">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="modalPharmacyNotificationLabel">Recordatorio</h4>
                        </div>
                        <div class="modal-body">
                            
                              <div class="callout callout-info">
                                <h4>Recordatorio de actualizacion de ubicación de consultorio o clinica</h4>

                                <p>Selecciona el dia y la hora del recordatorio</p>
                              </div>
                                <div class="row">
                                  <div class="col-sm-6">
                                    <div class="input-group">
                                      <input type="text" class="form-control"  name="notification_date" id="datetimepicker1" v-model="pharmacy.notification_datetime" @blur="onBlurDatetime">

                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="input-group">
                                      <input type="text" class="form-control"  name="notification_date" id="datetimepicker2" v-model="pharmacy.notification_hour" @blur="onBlurHour">

                                      <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                      </div>
                                    </div>
                                    
                                  </div>
                                  
                                </div> 


                                   
                              
                              
                           
                        </div>
                        <div class="modal-footer">
                          <!-- <button type="button" class="btn btn-primary btn-save-notification" v-bind:data-office="pharmacy.id">Guardar</button> -->
                          <button type="button" class="btn btn-danger" v-show="pharmacy.notification_date" @click="pharmacy.notification_date = ''">Quitar Notificación</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                      </div>
                    </div>
                  </div>


                </div>
                <!-- <div class="col-sm-3">
                  <div class="form-group">
                      <div class="col-sm-5">
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                            Ver ejemplo
                          </button>
                        </div>
                    </div>
                </div>-->
                <div class="col-sm-3">
                  <div class="form-group">
                      <div class="col-sm-5">
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPharmacyNotification" >
                            Actualizar coordenadas despues
                          </button>
                        </div>
                    </div>
                </div>
                
              
            
           
          </div>
          <div class="form-group">
              <div v-show="pharmacy.lat">
                 <label for="lat" class="col-sm-2 control-label">Prueba</label>
                  <a v-bind:href="'waze://?ll='+ pharmacy.lat +','+ pharmacy.lon +'&amp;navigate=yes'"  target="_blank" class="btn btn btn-app"><i class="fa fa-map-marker"></i> <strong>Abrir en Waze</strong></a>

                  <a v-bind:href="'http://maps.google.com/?saddr=Current+Location&daddr='+ pharmacy.lat +',' + pharmacy.lon" target="_blank" class="btn btn btn-app"><i class="fa fa-map-marker"></i> <strong>Abir en Google Maps</strong></a>
              </div>            
                              
          </div>
           <div class="form-group">
              <label for="file" class="col-sm-2 control-label">Logo</label>
               <div class="col-sm-4" v-show="pharmacy.id">
                <img v-bind:src="'/storage/pharmacies/'+ pharmacy.id+'/photo.jpg?'+ new Date().getTime()" alt="logo" style="height:100px;width:auto;">
               </div>
                <div class="col-sm-4">
                    <photo-upload @input="handleFileUpload" :value="value"></photo-upload>
                </div>
          </div>
         
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-danger" @click="save()">Guardar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
            </div>
          </div>
        
      </div>
     
      
  </div>
</template>

<script>
    import vSelect from 'vue-select'
    import FormError from './FormError.vue';

    export default {
      //props: ['clinic'],
      props: {
        pharmacy: {
          type: Object
        },
        url:{
          type:String,
          default: '/pharmacy/account'
        },
       
      },
      data () {
        return {
          
          provincias: [
          {
              title: 'San Jose',
              cantones : [
                  {
                      title: 'San Jose',
                      distritos:['Carmen','Merced','Hospital','Catedral','Zapote','San Francisco De Dos Rios','Uruca','Mata Redonda','Pavas','Hatillo','San Sebastián']
                  },
                  {
                      title: 'Escazu',
                      distritos:['Escazú','San Antonio','San Rafael']
                  },
                  {
                      title: 'Desamparados',
                      distritos:['Desamparados','San Miguel','San Juan De Dios','San Rafael Arriba','San Rafael Abajo','San Antonio','Frailes','Patarra','San Cristobal','Rosario','Damas','Gravilias','Los Guido']

                  },
                  {
                      title: 'Puriscal',
                      distritos:['Santiago','Mercedes Sur','Barbacoas','Grifo Alto','San Rafael','Candelarita','Desamparaditos','San Antonio','Chires']
                  },
                  {
                      title: 'Tarrazú',
                      distritos:['San Marcos','San Lorenzo','San Carlos']
                  },
                  {
                      title: 'Aserrí',
                      distritos:['Aserrí','Tarbaca','Vuelta De Jorco','San Gabriel','Legua','Monterrey','Salitrillos']
                  },
                  {
                      title: 'Mora',
                      distritos:['Colón','Guayabo','Tabarcia','Piedras Negras','Picagres','Jaris']
                  },
                  {
                      title: 'Goicoechea',
                      distritos:['Guadalupe','San Francisco','Calle Blancos','Mata De Platano','Ipís','Rancho Redondo','Purral']
                  },
                  {
                      title: 'Santa Ana',
                      distritos:['Santa Ana','Salitral','Pozos','Uruca','Piedades','Brasil']
                  },
                  {
                      title: 'Alajuelita',
                      distritos:['Alajuelita','San Josecito','San Antonio','Concepción','San Felipe']
                  },
                  {
                      title: 'Coronado',
                      distritos:['San Isidro','San Rafael','Dulce Nombre De Jesus','Patalillo','Cascajal']
                  },
                  {
                      title: 'Acosta',
                      distritos:['San Ignacio','Guaitil','Palmichal','Cangrejal','Sabanillas']
                  },
                  {
                      title: 'Tibas',
                      distritos:['San Juan','Cinco Esquinas','Anselmo Llorente','Leon Xiii','Colima']
                  },
                  {
                      title: 'Moravia',
                      distritos:['San Vicente','San Jeronimo','La Trinidad']
                  },
                  {
                      title: 'Montes de Oca',
                      distritos:['San Pedro','Sabanilla','Mercedes','San Rafael']

                  },
                  {
                      title: 'Turrubares',
                      distritos:['San Pablo','San Pedro','San Juan De Mata','San Luis','Carara']
                  },
                  {
                      title: 'Dota',
                      distritos:['Santa María','Jardin','Copey']
                  },
                  {
                      title: 'Curridabat',
                      distritos:['Curridabat','Granadilla','Sanchez','Tirrases']
                  },
                  {
                      title: 'Pérez Zeledón',
                      distritos:['San Isidro De El General','El General','Daniel Flores','Rivas','San Pedro','Platanares','Pejibaye','Cajon','Baru','Rio Nuevo','Páramo']
                  },
                  {
                      title: 'León Cortés',
                      distritos:['San Pablo','San Andres','Llano Bonito','San Isidro','Santa Cruz','San Antonio']
                  }

              ]
          },
          {
              title: 'Alajuela',
              cantones : [
                  {
                      title: 'Alajuela',
                      distritos:['Alajuela','San José','Carrizal','San Antonio','Guácima','San Isidro','Sabanilla','San Rafael','Rio Segundo','Desamparados','Turrucares','Tambor','Garita','Sarapiquí']
                  },
                  {
                      title: 'San Ramón',
                      distritos:['San Ramón','Santiago','San Juan','Piedades Norte','Piedades Sur','San Rafael','San Isidro','Angeles','Alfaro','Volio','Concepción','Zapotal','Peñas Blancas']
                  },
                  {
                      title: 'Grecia',
                      distritos:['Grecia','San Isidro','San José','San Roque','Tacares','Rio Cuarto','Puente De Piedra','Bolivar']
                  },
                  {
                      title: 'San Mateo',
                      distritos:['San Mateo','Desmonte','Jesús María','Labrador']
                  },
                  {
                      title: 'Atenas',
                      distritos:['Atenas','Jesús','Mercedes','San Isidro','Concepción','San José','Santa Eulalia','Escobal']
                  },
                  {
                      title: 'Naranjo',
                      distritos:['Naranjo','San Juan','San Miguel','Palmitos','El Rosario','San José','Cirrí Sur','San Jerónimo']
                  },
                  {
                      title: 'Palmares',
                      distritos:['Palmares','Zaragoza','Buenos Aires','Santiago','Candelaria','Esquipulas','La Granja']
                  },
                  {
                      title: 'Poás',
                      distritos:['San Pedro','San Juan','San Rafael','Carrillos','Sabana Redonda']
                  },
                  {
                      title: 'Orotina',
                      distritos:['Orotina','El Mastate','Hacienda Vieja','Coyolar','La Ceiba']
                  },
                  {
                      title: 'San Carlos',
                      distritos:['Quesada','Florencia','Buenavista','Aguas Zarcas','Venecia','Pital','La Fortuna','La Tigra','La Palmera','Venado','Cutris','Monterrey','Pocosol']
                  },
                  {
                      title: 'Zarcero',
                      distritos:['Zarcero','Laguna','Tapesco','Guadalupe','Palmira','Zapote','Brisas']
                  },
                  {
                      title: 'Valverde Vega',
                      distritos:['Sarchí Norte','Sarchí Sur','Toro Amarillo','San Pedro','Rodriguez']
                  },
                  {
                      title: 'Upala',
                      distritos:['Upala','Aguas Claras','San José o Pizote','Bijagua','Delicias','Dos Rios','Yolillal','Canalete']
                  },
                  {
                      title: 'Los Chiles',
                      distritos:['Los Chiles','Caño Negro','El Amparo','San Jorge']
                  },
                  {
                      title: 'Guatuso',
                      distritos:['San Rafael','Buenavista','Cote','Katira']
                  }


              ]
          },
          {
              title: 'Cartago',
              cantones : [
                  {
                      title: 'Cartago',
                      distritos:['Oriental','Occidental','Carmen','San Nicolás','Aguacaliente o San Francisco','Guadalupe o Arenilla','Corralillo','Tierra Blanca','Dulce Nombre','Llano Grande','Quebradilla']
                  },
                  {
                      title: 'Paraíso',
                      distritos:['Paraiso','Santiago','Orosi','Cachí','Llanos de Santa Lucía']
                  },
                  {
                      title: 'La Unión',
                      distritos:['Tres Rios','San Diego','San Juan','San Rafael','Concepción','Dulce Nombre','San Ramón','Rio Azul']
                  },
                  {
                      title: 'Jiménez',
                      distritos:['Juan Viñas','Tucurrique','Pejibaye']
                  },
                  {
                      title: 'Turrialba',
                      distritos:['Turrialba','La Suiza','Peralta','Santa Cruz','Santa Teresita','Pavones','Tuis','Tayutic','Santa Rosa','Tres Equis','La Isabel','Chirripó']
                  },
                  {
                      title: 'Alvarado',
                      distritos:['Pacayas','Cervantes','Capellades']
                  },
                  {
                      title: 'Oreamuno',
                      distritos:['San Rafael','Cot','Potrero Cerrado','Cipreses','Santa Rosa']
                  },
                  {
                      title: 'El Guarco',
                      distritos:['El Tejar','San Isidro','Tobosi','Patio De Agua']
                  }

              ]
          },
          {
              title: 'Heredia',
              cantones : [
                  {
                      title: 'Heredia',
                      distritos:['Heredia','Mercedes','San Francisco','Ulloa','Varablanca']
                  },
                  {
                      title: 'Barva',
                      distritos:['Barva','San Pedro','San Pablo','San Roque','Santa Lucía','San José de la Montaña']

                  },
                  {
                      title: 'Santo Domingo',
                      distritos:['Santo Domingo','San Vicente','San Miguel','Paracito','Santo Tomás','Santa Rosa','Tures','Para']
                  },
                  {
                      title: 'Santa Bárbara',
                      distritos:['Santa Bárbara','San Pedro','San Juan','Jesús','Santo Domingo','Puraba']
                  },
                  {
                      title: 'San Rafael',
                      distritos:['San Rafael','San Josecito','Santiago','Los Ángeles','Concepción']
                  },
                  {
                      title: 'San Isidro',
                      distritos:['San Isidro','San José','Concepción','San Francisco']
                  },
                  {
                      title: 'Belén',
                      distritos:['San Antonio','La Ribera','La Asuncion']
                  },
                  {
                      title: 'Flores',
                      distritos:['San Joaquín','Barrantes','Llorente']
                  },
                  {
                      title: 'San Pablo',
                      distritos:['San Pablo','Rincon De Sabanilla']
                  },
                  {
                      title: 'Sarapiquí',
                      distritos:['Puerto Viejo','La Virgen','Las Horquetas','Llanuras Del Gaspar','Cureña']
                  }

              ]
          },
          {
              title: 'Guanacaste',
              cantones : [
                  {
                      title: 'Liberia',
                      distritos:['Liberia','Cañas Dulces','Mayorga','Nacascolo','Curubande']
                  },
                  {
                      title: 'Nicoya',
                      distritos:['Nicoya','Mansion','San Antonio','Quebrada Honda','Samara','Nosara','Belen de Nosarita']
                  },
                  {
                      title: 'Santa Cruz',
                      distritos:['Santa Cruz', 'Bolson', 'Veintisiete de Abril', 'Tempate', 'Cartagena', 'Cuajiniquil', 'Diria', 'Cabo Velas', 'Tamarindo']
                  },
                  {
                      title: 'Bagaces',
                      distritos:['Bagaces','Fortuna','Mogote','Rio Naranjo']
                  },
                  {
                      title: 'Carrillo',
                      distritos:['Filadelfia', 'Palmira', 'Sardinal', 'Belen']
                  },
                  {
                      title: 'Cañas',
                      distritos:['Cañas','Palmira','San Miguel','Bebedero','Porozal']
                  },
                  {
                      title: 'Abangares',
                      distritos:['Juntas','Sierra','San Juan','Colorado']
                  },
                  {
                      title: 'Tilarán',
                      distritos:['Tilaran', 'Quebrada Grande', 'Tronadora', 'Santa Rosa', 'Libano', 'Tierras Morenas', 'Arenal']
                  },
                  {
                      title: 'Nandayure',
                      distritos:['Carmona','Santa Rita','Zapotal','San Pablo','Porvenir','Bejuco']
                  },
                  {
                      title: 'La Cruz',
                      distritos:['La Cruz', 'Santa Cecilia', 'Garita', 'Santa Elena']
                  },
                  {
                      title: 'Hojancha',
                      distritos:['Hojancha','Monte Romo','Puerto Carrillo','Huacas']
                  }

              ]
          },
          {
              title: 'Puntarenas',
              cantones : [
                  {
                      title: 'Puntarenas',
                      distritos:['Puntarenas','Pitahaya','Chomes','Lepanto','Paquera','Manzanillo','Guacimal','Barranca','Monte Verde','Isla Del Coco','Cóbano','Chacarita','Chira','Acapulco','El Roble','Arancibia']
                  },
                  {
                      title: 'Esparza',
                      distritos:['Espíritu Santo','San Juan Grande','Macacona','San Rafael','San Jerónimo']
                  },
                  {
                      title: 'Buenos Aires',
                      distritos:['Buenos Aires','Volcán','Potrero Grande','Boruca','Pilas','Colinas','Changuena','Biolley','Brunka']
                  },
                  {
                      title: 'Montes de Oro',
                      distritos:['Miramar','La Unión','San Isidro']
                  },
                  {
                      title: 'Osa',
                      distritos:['Puerto Cortés','Palmar','Sierpe','Bahía Ballena','Piedras Blancas','Bahía Drake']
                  },
                  {
                      title: 'Quepos',
                      distritos:['Quepos','Savegre','Naranjito']
                  },
                  {
                      title: 'Golfito',
                      distritos:['Golfito','Puerto Jiménez','Guaycara','Pavón']
                  },
                  {
                      title: 'Coto Brus',
                      distritos:['San Vito','Sabalito','Aguabuena','Limoncito','Pittier']
                  },
                  {
                      title: 'Parrita',
                      distritos:['San Vito']
                  },
                  {
                      title: 'Corredores',
                      distritos:['Corredor','La Cuesta','Canoas','Laurel']
                  },
                  {
                      title: 'Garabito',
                      distritos:['Jacó','Tárcoles']
                  }

              ]
          },
          {
              title: 'Limón',
              cantones : [
                  {
                      title: 'Limón',
                      distritos:['Limón','Valle La Estrella','Rio Blanco','Matama']
                  },
                  {
                      title: 'Pococí',
                      distritos:['Guapiles','Jiménez','Rita','Roxana','Cariari','Colorado','La Colonia']
                  },
                  {
                      title: 'Siquirres',
                      distritos:['Siquirres','Pacuarito','Florida','Germania','El Cairo','Alegría']
                  },
                  {
                      title: 'Talamanca',
                      distritos:['Bratsi','Sixaola','Cahuita','Telire']
                  },
                  {
                      title: 'Matina',
                      distritos:['Matina','Batán','Carrandi']
                  },
                  {
                      title: 'Guácimo',
                      distritos:['Guácimo','Mercedes','Pocora','Rio Jiménez','Duacari']
                  }

              ]
          }

          ],
         
          cantones: [],
          distritos: [],
          loader:false,
          newPharmacy:false,
        
          office: {
            lat : '',
            lon: '',
            notification_datetime: '',
            notification_hour: '',
            ide:'',
            ide_name:'',
           
          },
          selectedPharmacy:null,
          allPharmacies: [],
          errors: []
         
        
          
        }
      },
      components:{
        FormError,
        vSelect
      },
      methods: {
        onBlurDatetime(e){
          const value = e.target.value;
          console.log('onInput fired', value)
          
          //Add this line
          

          this.pharmacy.notification_datetime = value;
          this.$emit('input')
        },
         onBlurHour(e){
          const value = e.target.value;
          console.log('onInput fired', value)
          
          //Add this line

          this.pharmacy.notification_hour = value;
          this.$emit('input')
        },
        changeValue(value)
        {
          this.pharmacy.notification_date = this.pharmacy.notification_datetime + ' ' + this.pharmacy.notification_hour;
        },
        getGeolocation(){
       //obtener la posición actual y llamar a la función  "localitation" cuando tiene éxito
    
          var vm = this;
          window.navigator.geolocation.getCurrentPosition(vm.localitation);

       },
       localitation(geo){
       
      
          this.pharmacy.lat = geo.coords.latitude;
          this.pharmacy.lon = geo.coords.longitude;
          
         
       },
        onChangeProvince: function (event) {
          
          var cant = [];

          this.provincias.forEach(function(prov, index) {
              if(event.srcElement.value === prov.title) {
                   cant = prov.cantones;
              }
          });
          
          this.cantones = cant;
        },
        onChangeCanton: function (event) {
          
          var dist = [];

          this.cantones.forEach(function(cant, index) {
              if(event.srcElement.value === cant.title) {
                   dist = cant.distritos;
              }
          });
          
          this.distritos = dist;
        },
        edit(pharmacy) {
          
          var cant = [];
          var dist = [];

        
          this.provincias.forEach(function(prov, index) {
              if(pharmacy.province === prov.title) {
                   cant = prov.cantones;
              }
          });
          
          this.cantones = cant;

          this.cantones.forEach(function(cant, index) {
              if(pharmacy.canton === cant.title) {
                   dist = cant.distritos;
              }
          });
          
          this.distritos = dist;


          this.pharmacy = pharmacy;
          this.newPharmacy = true;
        
        
        },
      
        save() {
          this.loader = true;
          //var resource = this.$resource('/medic/account/offices');
            const config = {
                headers: {
                'content-type': 'multipart/form-data'
                }
            };
            let form = new FormData();
            let pharmacyObj = this.pharmacy
            
            Object.keys(pharmacyObj).forEach(function(key) {

                form.append(key, pharmacyObj[key]);
            });
           if(this.pharmacy.id)
           {
             
                this.$http.post(this.url+'/pharmacies', form, config).then(response => {
                    bus.$emit('alert', 'Farmacia Actualizada','success');
                     this.loader = false;
                     this.errors = [];
                     //this.office = {};
                     this.newOffice = false;

                    window.location.href = this.url +"/edit?tab=pharmacies";
                   

                }, response => {
                       console.log(response.data)
                    this.loader = false;
                    this.loader_message ="Error al guardar cambios";
                    this.errors = response.data;
                });

           }
            $(window).scrollTop(580);
             bus.$emit('clearImage');

      },
       handleFileUpload(file){
         
          this.pharmacy.file = file
        // let form = new FormData();
        // form.append('photo', file);

        // // or this.$http
        // axios.post(`/api/upload`, form).then(res => {
            
        // }, res => {

        // });
    },
	    
         
          selectPharmacy(farmacia) {
            
            if(farmacia){
              var cant = [];
              var dist = [];

            
              this.provincias.forEach(function(prov, index) {
                  if(farmacia.province === prov.title) {
                       cant = prov.cantones;
                  }
              });
              
              this.cantones = cant;

              this.cantones.forEach(function(cant, index) {
                  if(farmacia.canton === cant.title) {
                       dist = cant.distritos;
                  }
              });
              
              this.distritos = dist;
              this.pharmacy = farmacia;
            
                
              
            }

          
          
           }
    

      },
      created () {
             console.log('Component ready. pharmacy')

             //this.office = this.clinic;
             this.selectPharmacy(this.pharmacy)

             this.$on('input', this.changeValue);
        }
    }
</script>