$(function () {
    
      function obtainGeolocation(){
       //obtener la posición actual y llamar a la función  "localitation" cuando tiene éxito
       window.navigator.geolocation.getCurrentPosition(localitation);
       }
       function localitation(geo){
       
      // En consola nos devuelve el Geoposition object con los datos nuestros
          
          $('input[name="lat"]').val(geo.coords.latitude);
          $('input[name="lon"]').val(geo.coords.longitude);
          
          var speciality = $('select[name="speciality"]').val();

          if(speciality === undefined || speciality != '')
            $('form').submit();
          else
          {
            alert('Selecciona una especialidad');

          }
       }
       //llamando la funcion inicial para ver trabajar la API
       

       $('.btn-geo').on('click', function (e) {
           obtainGeolocation();
       });


        // provincias cantones y distritos
         
        var selectProvincias = $('select[name=province]'),
        selectCantones = $('select[name=canton]'),
        selectDistritos = $('select[name=district]'),
        ubicaciones = window.provincias,
        cantonesOfselectedProvince = [],
        selectedCanton = $('input[name=selectedCanton]').val(),
        selectedDistrict = $('input[name=selectedDistrict]').val();
        
        selectCantones.empty();
        selectDistritos.empty();

  
        selectProvincias.change(function() {
          
            var $this =  $(this);
            selectCantones.empty();
       
            $.each(ubicaciones, function(index,provincia) {

                if(provincia.title == $this.val()){
                       selectCantones.append('<option value=""></option>');
                      $.each(provincia.cantones, function(index,canton) {
                        
                         // cantones.append('<option value="' + canton.title + '">' + canton.title + '</option>');

                          var o = new Option(canton.title, canton.title);
                          
                          if(canton.title == selectedCanton)      
                            o.selected=true;

                          selectCantones.append(o);

                          cantonesOfselectedProvince.push(canton);
                      });
                      
                      selectCantones.change();
                  }
            });

        });

        selectCantones.change(function() {
          
            var $this =  $(this);
            selectDistritos.empty();
            //cantones.append('<option value="Todos">Todos</option>');
            $.each(cantonesOfselectedProvince, function(index,canton) {
                
                if(canton.title == $this.val()){
                      selectDistritos.append('<option value=""></option>');
                      $.each(canton.distritos, function(index,distrito) {

                          //distritos.append('<option value="' + distrito + '">' + distrito + '</option>');
                          var o = new Option(distrito, distrito);
                          
                          if(distrito == selectedDistrict)      
                            o.selected=true;

                          selectDistritos.append(o);
                      });
                  }
            });

        });

      
      selectProvincias.change();
      /*setTimeout(function(){
             
             selectProvincias.change();

          }, 100);*/




     $('#myModal').on('shown.bs.modal', function (event) {
          
          var button = $(event.relatedTarget)
          var lat = button.attr('data-lat');
          var lon = button.attr('data-lon');
          var address = button.attr('data-address');
              
      
        $(".share").jsSocials({
            shares: ["email", "twitter", "facebook", "googleplus", "whatsapp"],
            url: "http://maps.google.com/?saddr=Current+Location&daddr="+lat +"," + lon,
            text: address,
            showLabel: false,
            showCount: false,
            shareIn: "popup",
           
        });
          
        
     
       
      });



    });