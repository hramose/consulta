@extends('layouts.login')
@section('css')
  <link rel="stylesheet" href="/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
 
@endsection
@section('content')

  <div class="register-box register-box-patient">
  <div class="register-logo">
    <a href="/"><b>{{ config('app.name', 'Laravel') }}</b></a>
  </div>
   
  <div class="register-box-body">
    <div class="callout callout-info"><h4>Ya casi terminas!</h4> <p>Agrega los siguientes datos de la clínica para finalizar el rergistro.</p></div>
    <form method="POST" action="{{ url('/clinic/register/office') }}" class="form-horizontal register-patient"  enctype="multipart/form-data">
         {{ csrf_field() }}
         
         <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="name" placeholder="Nombre de la clínica u hospital" value="" required>
               @if ($errors->has('name'))
                  <span class="help-block">
                      <strong>{{ $errors->first('name') }}</strong>
                  </span>
              @endif
              </div>
          </div>
          <div class="form-group">

            <div class="col-sm-12">
              <input type="text" class="form-control" name="address" placeholder="Dirección" value="{{ old('address') }}" required>
               @if ($errors->has('address'))
                  <span class="help-block">
                      <strong>{{ $errors->first('address') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
         
            <div class="col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="province" id="province" placeholder="-- Selecciona provincia --" required>
                <option value="" style="color: #c3c3c3">Provincia</option>
                <option>Guanacaste</option>
                <option>San Jose</option>
                <option>Heredia</option>
                <option>Limon</option>
                <option>Cartago</option>
                <option>Puntarenas</option>
                 <option>Alajuela</option>
              </select>
              
              
               @if ($errors->has('province'))
                  <span class="help-block">
                      <strong>{{ $errors->first('province') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
         
            <div class="col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="canton" id="canton" placeholder="-- Selecciona canton --" required>
                <option value="">Canton</option>
               
               
              </select>
              
               @if ($errors->has('canton'))
                  <span class="help-block">
                      <strong>{{ $errors->first('canton') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
         
            <div class="col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="district" id="district" placeholder="-- Selecciona district --" required>
                <option value="">Distrito</option>
                
               
              </select>
              
               @if ($errors->has('district'))
                  <span class="help-block">
                      <strong>{{ $errors->first('district') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="phone" placeholder="Teléfono" value="{{ old('phone') }}" required>
               @if ($errors->has('phone'))
                  <span class="help-block">
                      <strong>{{ $errors->first('phone') }}</strong>
                  </span>
              @endif
            </div>
          </div>
           <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="ide" placeholder="Cédula Jurídica" value="{{ old('ide') }}" required>
               @if ($errors->has('ide'))
                  <span class="help-block">
                      <strong>{{ $errors->first('ide') }}</strong>
                  </span>
              @endif
            </div>
          </div>
           <div class="form-group">
           
            <div class="col-sm-12">
              <input type="text" class="form-control" name="ide_name" placeholder="Nombre Jurídico" value="{{ old('ide_name') }}" required>
               @if ($errors->has('ide_name'))
                  <span class="help-block">
                      <strong>{{ $errors->first('ide_name') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="form-group">
            

           
            <div class="col-sm-12">
              <input type="file" class="form-control" name="file" placeholder="Logo">
               <span class="">Logo (jpg - png - bmp - jpeg)</span>
            </div>
          </div>
         
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
              <button type="submit" class="btn btn-danger">Finalizar</button>
            </div>
          </div>
    </form>


  </div>
  <!-- /.form-box -->
</div>

		
@endsection
@section('scripts')
<script src="/js/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/js/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/js/plugins/select2/select2.full.min.js"></script>

<script>
  $(function () {
    var ubicaciones = [
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
                      distritos:['Parrita']
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

          ];

       var provincias = $('#province'),
        cantones = $('#canton'),
        distritos =  $('#district');
       

    //provincias.empty();
    cantones.empty();
    distritos.empty();
    //provincias.append('<option value=""></option>');
   /* $.each(ubicaciones, function(index,provincia) {
        provincias.append('<option value='+ provincia.name_id +'>' + provincia.title + '</option>');
    });*/

   // $('#all_country').checked();
    provincias.change(function() {
        var $this =  $(this);
        cantones.empty();
        cantones.append('<option value="">Canton</option>');
        $.each(ubicaciones, function(index,provincia) {

            if(provincia.title == $this.val()){
                $.each(provincia.cantones, function(index,canton) {

                    cantones.append('<option value="' + canton.title + '">' + canton.title + '</option>');
                });
              }
        });

    });
     cantones.change(function() {
        var $this =  $(this);
        distritos.empty();
        distritos.append('<option value="">Distrito</option>');
        $.each(ubicaciones, function(index,provincia) {
           
            if(provincia.title == provincias.val())
                $.each(provincia.cantones, function(index,canton) {
                  
                     if(canton.title == $this.val())
                     {
                      $.each(canton.distritos, function(index,distrito) {

                          distritos.append('<option value="' + distrito + '">' + distrito + '</option>');
                      });
                      
                     }
                });
        });

    });
    /*
    $("select[name='province']").select2({
      placeholder: "Selecciona Provincia",
      allowClear: true
    });
    $("select[name='gender']").select2({
      placeholder: "Selecciona Genero",
      allowClear: true
    });
    $("[data-mask]").inputmask();*/
  });
</script>
@endsection
