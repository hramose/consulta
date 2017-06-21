<template>	
	<div class="form-horizontal">
     
      <div class="form-group">
        <label for="office_name" class="col-sm-2 control-label">Buscar</label>
         
              <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                    <div class="col-sm-12">
                    <v-select :debounce="250" :on-search="getOffices"  :options="allOffices" placeholder="Buscar consultorio..." label="name" :on-change="selectOffice" :value.sync="selectedValue" ></v-select>
                    </div>
                </div>
                    
                  
              </div>
              <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                    <div class="col-sm-10">
                    <a href="#" class="btn btn-success " @click="assignToMedic()" v-show="office.id">Agregar</a>
                    <a href="#" class="btn btn-default " @click="nuevo()" v-show="officeNotFound">Crear Consultorio Nuevo</a>
                    </div>
                </div>
                    
                  
              </div> 
        
          
        <!--<div class="col-sm-10">

           <input type="text" class="form-control" name="name" placeholder="Nombre del consultorio" v-model="office.name" >
          <form-error v-if="errors.name" :errors="errors" style="float:right;">
              {{ errors.name[0] }}
          </form-error> 
          </div>-->
      </div>
      <div class="newform" v-show="newOffice">

            <div class="form-group">
              <label for="office_name" class="col-sm-2 control-label">Nombre</label>
              <div class="col-sm-10">

                 <input type="text" class="form-control" name="name" placeholder="Nombre del consultorio, clínica u hospital" v-model="office.name" :disabled="office.id && office.type != 'Consultorio Independiente'">
                <form-error v-if="errors.name" :errors="errors" style="float:right;">
                    {{ errors.name[0] }}
                </form-error> 
              </div>
            </div>
           <div class="form-group">
            <label for="office_type" class="col-sm-2 control-label">Tipo</label>

            <div class="col-sm-10">
              <select class="form-control " style="width: 100%;" name="type" placeholder="-- Selecciona tipo --"  v-model="office.type" :disabled="office.id && office.type != 'Consultorio Independiente'">
                <option disabled="disabled"></option>
                <option v-for="item in tipos" v-bind:value="item">{{ item }}</option>
                
              </select>
              <form-error v-if="errors.type" :errors="errors" style="float:right;">
                  {{ errors.type[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="office_address" class="col-sm-2 control-label">Dirección</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="address" placeholder="Dirección"  v-model="office.address" :disabled="office.id && office.type != 'Consultorio Independiente'">
              <form-error v-if="errors.address" :errors="errors" style="float:right;">
                  {{ errors.address[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="office_province" class="col-sm-2 control-label">Provincia</label>

            <div class="col-sm-10">
              <select class="form-control " style="width: 100%;" name="province" placeholder="-- Selecciona provincia --"  v-model="office.province" v-on:change="onChangeProvince" :disabled="office.id && office.type != 'Consultorio Independiente'">
                <option disabled="disabled"></option>
                <option v-for="item in provincias" v-bind:value="item.title">{{ item.title }}</option>
                
              </select>
              <form-error v-if="errors.province" :errors="errors" style="float:right;">
                  {{ errors.province[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="office_canton" class="col-sm-2 control-label">Canton</label>

            <div class="col-sm-10">
              <select class="form-control " style="width: 100%;" name="canton" placeholder="-- Selecciona canton --"  v-model="office.canton" v-on:change="onChangeCanton" :disabled="office.id && office.type != 'Consultorio Independiente'">
                <option disabled="disabled"></option>
                <option v-for="item in cantones" v-bind:value="item.title">{{ item.title }}</option>
                
              </select>
              <form-error v-if="errors.canton" :errors="errors" style="float:right;">
                  {{ errors.canton[0] }}
              </form-error>
            </div>
          </div>
          <div class="form-group">
            <label for="office_district" class="col-sm-2 control-label">Distrito</label>

            <div class="col-sm-10">
              <select class="form-control " style="width: 100%;" name="district" placeholder="-- Selecciona distrito --"  v-model="office.district" :disabled="office.id && office.type != 'Consultorio Independiente'">
                <option disabled="disabled"></option>
                <option v-for="item in distritos" v-bind:value="item">{{ item }}</option>
                
              </select>
              <form-error v-if="errors.district" :errors="errors" style="float:right;">
                  {{ errors.district[0] }}
              </form-error>
            </div>
          </div>
          
          <div class="form-group">
            <label for="office_phone" class="col-sm-2 control-label">Teléfono</label>

            <div class="col-sm-10">
              <input type="text" class="form-control" name="phone" placeholder="Teléfono" v-model="office.phone">
              <form-error v-if="errors.phone" :errors="errors" style="float:right;">
                  {{ errors.phone[0] }}
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
                        <input type="text" class="form-control" name="lat" placeholder="10.637875" v-model="office.lat" :disabled="office.id && office.type != 'Consultorio Independiente'">
                      </div>
                    </div>
                  </div>
                  
                   
                </div>
                <div class="col-sm-3">
                   <div class="form-group">
                    <div class="col-sm-10">
                      <div class="input-group">
                        <span class="input-group-addon">lon:</span>
                        <input type="text" class="form-control" name="lon" placeholder="-85.434431" v-model="office.lon" :disabled="office.id && office.type != 'Consultorio Independiente'">
                      </div>
                    </div>
                  </div>
                   
                </div>
               
                <div class="col-sm-3">
              
                  <div class="form-group">

                     
                    <div class="col-sm-6">
                    
                      
                       <button type="button" class="btn btn-default btn-geo" @click="getGeolocation" v-show="office.type == 'Consultorio Independiente'"><i class="fa fa-"></i>Tu ubicación Actual</button>
                     
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
                  <div class="modal fade" id="modalOfficeNotification" tabindex="-1" role="dialog" aria-labelledby="modalOfficeNotificationLabel">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="modalOfficeNotificationLabel">Recordatorio</h4>
                        </div>
                        <div class="modal-body">
                            
                              <div class="callout callout-info">
                                <h4>Recordatorio de actualizacion de ubicación de consultorio o clinica</h4>

                                <p>Selecciona el dia y la hora del recordatorio</p>
                              </div>
                                <div class="row">
                                  <div class="col-sm-6">
                                    <div class="input-group">
                                      <input type="text" class="form-control"  name="notification_date" id="datetimepicker1" v-model="office.notification_datetime" @blur="onBlurDatetime">

                                      <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="input-group">
                                      <input type="text" class="form-control"  name="notification_date" id="datetimepicker2" v-model="office.notification_hour" @blur="onBlurHour">

                                      <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                      </div>
                                    </div>
                                    
                                  </div>
                                  
                                </div> 


                                   
                              
                              
                           
                        </div>
                        <div class="modal-footer">
                          <!-- <button type="button" class="btn btn-primary btn-save-notification" v-bind:data-office="office.id">Guardar</button> -->
                          <button type="button" class="btn btn-danger" v-show="office.notification_date" @click="office.notification_date = ''">Quitar Notificación</button>
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
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalOfficeNotification" v-show="office.type == 'Consultorio Independiente'">
                            Actualizar coordenadas despues
                          </button>
                        </div>
                    </div>
                </div>
                
              
            
           
          </div>
          <div class="form-group">
              <div v-show="office.lat">
                 <label for="lat" class="col-sm-2 control-label">Prueba</label>
                  <a v-bind:href="'waze://?ll='+ office.lat +','+ office.lon +'&amp;navigate=yes'"  target="_blank" class="btn btn btn-app"><i class="fa fa-map-marker"></i> <strong>Abrir en Waze</strong></a>

                  <a v-bind:href="'http://maps.google.com/?saddr=Current+Location&daddr='+ office.lat +',' + office.lon" target="_blank" class="btn btn btn-app"><i class="fa fa-map-marker"></i> <strong>Abir en Google Maps</strong></a>
              </div>            
                              
          </div>
         
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-danger" @click="save()" :disabled="loader">Guardar</button><img src="/img/loading.gif" alt="Cargando..." v-show="loader">
            </div>
          </div>
        
      </div>
     
      <h3>Tus Consultorios o clínicas</h3>
      <ul id="offices-list" class="todo-list ui-sortable" v-show="consultorios.length">
       
        <li v-for="item in consultorios">
          <!-- todo text -->
          <a href="#clinics"><i class="fa fa-building"></i><span><span class="text" @click="edit(item)">{{ item.name }}</span></span></a>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            <!-- <i class="fa fa-edit" @click="edit(item)"></i> -->
            <i class="fa fa-trash-o delete" @click="remove(item)"></i>
          </div>
        </li>
       
      </ul>
  </div>
</template>

<script>
    import vSelect from 'vue-select'
    import FormError from './FormError.vue';

    export default {
      props: ['offices'],
     
      data () {
        return {
          consultorios: [],
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
          tipos:['Consultorio Independiente', 'Clínica Privada'],
          cantones: [],
          distritos: [],
          loader:false,
          newOffice:false,
        
          office: {
            lat : '',
            lon: '',
            notification_datetime: '',
            notification_hour: '',
            type:''
          },
          selectedValue:null,
          allOffices: [],
          errors: [],
          officeNotFound:false
         
        
          
        }
      },
      components:{
        FormError,
        vSelect
      },
      methods: {
        nuevo(){
          if(this.newOffice && this.office.id) {
           


          }else{
            this.newOffice = !this.newOffice;
          
          }

           this.office = {
                lat : '',
                lon: '',
                notification_datetime: '',
                notification_hour: '',
                type:''
              };
          this.selectedValue = null;
          this.allOffices = [];
          
        },
        onBlurDatetime(e){
          const value = e.target.value;
          console.log('onInput fired', value)
          
          //Add this line
          

          this.office.notification_datetime = value;
          this.$emit('input')
        },
         onBlurHour(e){
          const value = e.target.value;
          console.log('onInput fired', value)
          
          //Add this line

          this.office.notification_hour = value;
          this.$emit('input')
        },
        changeValue(value)
        {
          this.office.notification_date = this.office.notification_datetime + ' ' + this.office.notification_hour;
        },
        getGeolocation(){
       //obtener la posición actual y llamar a la función  "localitation" cuando tiene éxito
    
          var vm = this;
          window.navigator.geolocation.getCurrentPosition(vm.localitation);

       },
       localitation(geo){
       
      
          this.office.lat = geo.coords.latitude;
          this.office.lon = geo.coords.longitude;
          
         
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
        edit(office) {
          
          var cant = [];
          var dist = [];

        
          this.provincias.forEach(function(prov, index) {
              if(office.province === prov.title) {
                   cant = prov.cantones;
              }
          });
          
          this.cantones = cant;

          this.cantones.forEach(function(cant, index) {
              if(office.canton === cant.title) {
                   dist = cant.distritos;
              }
          });
          
          this.distritos = dist;


          this.office = office;
          this.newOffice = true;
        
        
        },
        assignToMedic() {

              this.loader = true;
                this.$http.post('/medic/account/offices/'+ this.office.id+'/assign', this.office).then((response) => {
                      console.log(response.status);
                      console.log(response.data);
                      if(response.status == 200 && response.data)
                      {
                      
                        if(response.data.id)
                          this.consultorios.push(response.data);

                        bus.$emit('alert', 'Consultorio Agregado','success');
                        this.office = {};
                        this.newOffice = false;
                        this.errors = [];
                        this.selectedValue = null;
                      }
                     this.loader = false;
                }, (response) => {
                    console.log('error al guardar consultorio')
                    this.loader = false;
                     this.errors = response.data;
                });
          
              
              $(window).scrollTop(580);

        },
        save() {

          //var resource = this.$resource('/medic/account/offices');
           this.loader = true;
           if(this.office.id)
           {
             var resource = this.$resource('/medic/account/offices/'+ this.office.id);

                resource.update(this.office).then((response) => {
                    
                     bus.$emit('alert', 'Consultorio Actualizado','success');
                     this.loader = false;
                     this.errors = [];
                     this.office = {};
                     this.newOffice = false;
                     this.selectedValue = null;
                     this.allOffices = [];
                }, (response) => {
                    console.log(response.data)
                    this.loader = false;
                    this.loader_message ="Error al guardar cambios";
                    this.errors = response.data;
                });

           }else{
              this.$http.post('/medic/account/offices', this.office).then((response) => {
                    console.log(response.status);
                    console.log(response.data);
                    if(response.status == 200 && response.data)
                    {
                      this.consultorios.push(response.data);
                      if(this.office.type != 'Consultorio Independiente')
                        bus.$emit('alert', 'Consultorio Agregado - Esperando confirmación por parte del administrador de la clinica u hospital','success');
                      else
                        bus.$emit('alert', 'Consultorio Agregado','success');
                      this.office = {};
                      this.newOffice = false;
                      this.errors = [];
                      this.selectedValue = null;
                      this.allOffices = [];
                    }
                   this.loader = false;
              }, (response) => {
                  console.log('error al guardar consultorio')
                  this.loader = false;
                   this.errors = response.data;
              });
        
            }
            $(window).scrollTop(580);

      },
	     

      remove(item){
          let $vm = this;
          swal({
            title: 'Deseas eliminar el consultorio o clinica?',
            text: "Requerda que te desvincularás de la clinica!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Eliminar!',
            cancelButtonText: 'Cancelar'
          }).then(function () {

            $vm.$http.delete('/medic/account/offices/'+item.id).then((response) => {

                  if(response.status == 200)
                  {
                     var index =$vm.consultorios.indexOf(item)
                    $vm.consultorios.splice(index, 1);

                    bus.$emit('alert', 'Consultorio Eliminado','success');
                  }
                  $vm.loader = false;

              }, (response) => {
                  
                   bus.$emit('alert', 'Error al eliminar el consultorio', 'danger');
                   $vm.loader = false;
              });

            swal(
              'Eliminado!',
              'Consultorio Eliminado.',
              'success'
            )

          }, function(dismiss) {
            
          });
            


          },
           getOffices:_.debounce(function(search, loading) {
             

           loading(true)
           
           let queryParam = {
                ['q']: search
              }
            this.$http.get('/medic/offices/list', {params: Object.assign(queryParam, this.data)})
            .then(resp => {
               
               this.allOffices = resp.data

               if(this.allOffices.length <= 0)
                  this.officeNotFound = true;
                else
                  this.officeNotFound = false;

               loading(false)
            })
          

        }, 500),
        
          selectOffice(clinica) {
            this.office = {
                lat : '',
                lon: '',
                notification_datetime: '',
                notification_hour: '',
                type:''
              };
            if(clinica){
              var cant = [];
              var dist = [];

            
              this.provincias.forEach(function(prov, index) {
                  if(clinica.province === prov.title) {
                       cant = prov.cantones;
                  }
              });
              
              this.cantones = cant;

              this.cantones.forEach(function(cant, index) {
                  if(clinica.canton === cant.title) {
                       dist = cant.distritos;
                  }
              });
              
              this.distritos = dist;
              this.office = clinica;
              this.selectedValue = clinica;
              /*this.appointment.title = clinica.name;
              this.appointment.office_info = JSON.stringify(clinica);*/
                
              
            }

          
          
           }
    

      },
      created () {
             console.log('Component ready. office')

             this.consultorios = this.offices;
             this.$on('input', this.changeValue);
        }
    }
</script>