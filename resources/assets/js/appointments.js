$(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        var eventObject = {
          title: $.trim($(this).text()), // use the element's text as the event title
          user_id: $(this).data('doctor'),
          patient_id: $(this).data('patient')
          
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);
       
        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });
       
      });
    }

    ini_events($('#external-events div.external-event'));

    /** load events from db **/
    function fetch_events() {

        $.ajax({
            type: 'GET',
            url: '/medic/appointments/list',
            data: {},
            success: function (resp) {
                console.log(resp);

                var appointments = [];

                $.each(resp, function( index, item ) {
                   
                    item.allDay = parseInt(item.allDay); // = false;
                    
                    if(item.patient_id == 0){
                      item.rendering = 'background';
                    }
                    
                    //debugger

                    appointments.push(item);
                });
               
                initCalendar(appointments);
                
            },
            error: function () {
                console.log('Error - '+ resp);

            }
        });


    }
    function fetch_offices() {

        $.ajax({
            type: 'GET',
            url: '/medic/account/offices/list',
            data: {},
            success: function (resp) {
                console.log(resp);

                var offices = [];
                var currColor = "#3c8dbc";
                $.each(resp, function( index, item ) {
                   
                    

                    offices.push(item);
                    
                    var event = $("<div />");
                    event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
                    event.attr('data-patient', 0);
                    event.attr('data-doctor', $('input[name=user_id]').val());
                    event.html('');
                    event.html(item.name);
                    $('#external-events').prepend(event);

                    //Add draggable funtionality
                    ini_events(event);



                });
               
                
                
                
            },
            error: function () {
                console.log('Error - '+ resp);

            }
        });


    }

    fetch_events();
    fetch_offices();


    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();

    function initCalendar(appointments)
    {

      $('#calendar').fullCalendar({
          locale: 'es',
          defaultView: 'agendaWeek',
          timeFormat: 'h(:mm)a',
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          //Random default events
          events: appointments,
          forceEventDuration: true,
          editable: true,
          droppable: true, // this allows things to be dropped onto the calendar !!!
          eventOverlap: false,
          drop: function (date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');
          
            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);
            
            // assign it the date that was reported
            copiedEventObject.start = date;
           
            
            copiedEventObject.allDay = false;//allDay;
            copiedEventObject.backgroundColor = $(this).css("background-color");
            copiedEventObject.borderColor = $(this).css("border-color");
            copiedEventObject.overlap = false;
            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)

            var _id = $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)[0]._id; // get _id from event in the calendar (this is for if user will remove the event)
            
           
            saveAppointment(copiedEventObject, _id);

           
            if($(this).data('patient'))
              $(this).remove(); // remover de citas sin agendar
         
           
          },
          eventResize: function(event, delta, revertFunc) {

              updateAppointment(event, revertFunc);
          
           
          },
          eventDrop: function(event, delta, revertFunc) {
            
              updateAppointment(event, revertFunc);
              

          },
          eventRender: function(event, element) {
            element.append( "<span class='closeon fa fa-trash'></span>" );
            element.find(".closeon").click(function() {
               deleteAppointment(event._id, event);
            });
            if (event.rendering == 'background') {
                element.append('<h3>'+ event.title + '</h3>');
            }

            

        },
        
      });

    }

    function isOverlapping(event) {
     
        var array = $('#calendar').fullCalendar('clientEvents');
         
          for(i in array){
              if (event.end >= array[i].start._i && event.start <= array[i].end._i){
                 return true;
              }
          }
          return false;
    }
    
    /* SAVE UPDATE DELETE EVENTS */
    function crud(method, url, data, revertFunc) {
      
      $.ajax({
            type: method || 'POST',
            url: url,
            data: data,
            success: function (resp) {
              
              if(method == "POST")
              {
                $('#calendar').fullCalendar( 'removeEvents', data.idRemove)
                 //debugger
                  /*if(isOverlapping(resp))
                    resp.allDay = 1; // si se montan poner el evento en todo el dia*/
                
                resp.allDay = parseInt(resp.allDay);
               

                $('#calendar').fullCalendar('renderEvent', resp, true);
              }
               if(method == "DELETE")
               {
                 if(resp)
                 {
                  $('#infoBox').addClass('alert-danger').html('No se puede eliminar consulta ya que se encuentra iniciada!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

                   return
                  }

                  $('#calendar').fullCalendar('removeEvents',data.idRemove);
              
               }
               
               if(method == "PUT")
               {
                 if(resp == '')
                 {
                  $('#infoBox').addClass('alert-danger').html('No se puede cambiar de dia la consulta ya que se encuentra iniciada!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);


                    revertFunc();
                    
                   return
                  }

                  
              
               }
                
            },
            error: function () {
              console.log('error saving appointment');

            }
        });
    }
    
    function saveAppointment(event, idRemove)
    {
      
      var appointment = {
        title : event.title,
        date : event.start.format("YYYY-MM-DD"),
        start : event.start.format(),
        end : (event.end) ? event.end.format() : event.start.add(2, 'hours').format(),
        backgroundColor: event.backgroundColor, //Success (green)
        borderColor: event.borderColor,
        user_id: event.user_id,
        patient_id: (event.patient_id) ? event.patient_id : 0,
        idRemove: idRemove,
        allDay: 0
        
      };

      if(isOverlapping(appointment)){
        appointment.allDay = 1;
      }

      crud('POST', '/medic/appointments', appointment)

    }

     function updateAppointment(event, revertFunc)
    {
      
      var appointment = {
        subject : event.title,
        date : event.start.format("YYYY-MM-DD"),
        start : event.start.format(),
        end : (event.end) ? event.end.format() : event.start.add(2, 'hours').format(),
        backgroundColor: event.backgroundColor, //Success (green)
        borderColor: event.borderColor,
        user_id: event.user_id,
        patient_id: event.patient_id,
        id: event.id,
        allDay: 0
      };
      
      crud('PUT', '/medic/appointments/'+appointment.id, appointment, revertFunc)

    }

    function deleteAppointment(id)
    {

      crud('DELETE', '/medic/appointments/'+ id + '/delete', {idRemove:id})
     
    }

    /* ADDING EVENTS */
    var currColor = "#3c8dbc"; //Red by default
    //Color chooser button
    var colorChooser = $("#color-chooser-btn");
    $("#color-chooser > li > a").click(function (e) {
      e.preventDefault();
      //Save color
      currColor = $(this).css("color");
      //Add color effect to button
      $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
    });

    function createEvent()
    {
      var val = $("#new-event").val();
      var valSelect = $(".search-patients").val();
      if (val.length == 0 || valSelect.length == 0) {
        return;
      }
     

      //Create events
      var event = $("<div />");
      event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
      event.attr('data-patient', $(".search-patients").val());
      event.attr('data-doctor', $('input[name=user_id]').val());
      event.html('');
      event.html(val + ' - '+ $(".search-patients").text());
      $('#external-events').prepend(event);

      //Add draggable funtionality
      ini_events(event);

      //Remove event from text input
      $("#new-event").val("");
      $(".search-patients").val("").trigger('change');
      $(".search-patients").text("").trigger('change');
    }

    $("#new-event").keypress(function( e ) {
        if(e.which == 13) {
            createEvent();
        }
    });

    $("#add-new-event").click(function (e) {
      e.preventDefault();

      createEvent();
      
    });

    $(".search-patients").select2({
            placeholder: "Buscar paciente",
            ajax: {
              url: "/medic/patients/list",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  q: params.term // search term
                  
                };
              },
              processResults: function (data) {
               
               // console.log(data.data);
                var items = []
                
                $.each(data.data, function (index, value) {
                    item = {
                      id: value.id,
                      text: value.first_name
                    }
                    items.push(item);
                })
              
                    
                return {
                  results: items,
                  
                };
              }

            
             
            }
     });



  });
/*$('#searchPatients').on('keypress', function(event) {

        if (event.keyCode == 13) {
            event.preventDefault();
        }

    });
    $('#searchPatients').on('keyup', function(event) {
        search();
    });
    function search() {

        var input = $('#searchPatients'),
            key =input.val(),
            self = this;

        if(key.length >=3 ){

            $('.loading-search').removeClass('hidden');
            clearTimeout( this.timer );
            this.timer = setTimeout(function () {
                console.log('search ' + key);
                 
                 $.ajax({
                  type: 'GET',
                  url: '/medic/patients/list',
                  data: {search: key},
                  success: function (resp) {
                      console.log(resp.data);
                      $('.search-list').html('');
                      $.each(resp.data, function(index, value){

                          var li = $('<li />').html(value.first_name);
                          li.attr('data-id',value.id);
                          console.log(value.id);
                          $('.search-list').append(li);
                      });

                      //$('search-list').
                     
                  },
                  error: function () {
                     
                    
                  }
              });

            },200);


        }else if(key.length == 0){
            $('.dropdown').removeClass('open');
            
        }




    }
    $('.search-list').on('click','li', function (e) {
        //console.log($(this).data('id'));


    });*/