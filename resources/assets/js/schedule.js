$(function () {

    //para quitar el popup tocando fuera del item
    $('body').on('click', function (e) {
        $('[data-toggle="popover"],[data-original-title]').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {                
                (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
            }

        });
    });

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
     
      ele.each(function () {

        var eventObject = {
          title: $.trim($(this).text()), // use the element's text as the event title
          user_id: $(this).data('doctor'),
          patient_id: $(this).data('patient'),
          created_by: $(this).data('createdby')
          
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
    function fetch_events_from_medic() {

        $.ajax({
            type: 'GET',
            url: '/medics/'+ $('input[name="medic_id"]').val() +'/appointments/list',
            data: {},
            success: function (resp) {
                console.log(resp);

                var appointments = [];

                $.each(resp, function( index, item ) {
                   
                    item.allDay = parseInt(item.allDay); // = false;
                    
                    if(item.patient_id == 0 || item.created_by != $('input[name="created_by"]').val()){
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
    

    fetch_events_from_medic();
   


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
          defaultTimedEventDuration: '01:00:00',
          editable: false,
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

           
            /*if($(this).data('patient'))
              $(this).remove(); // remover de citas sin agendar
         */
           
          },
          eventResize: function(event, delta, revertFunc) {

              updateAppointment(event, revertFunc);
          
           
          },
          eventDrop: function(event, delta, revertFunc) {
            
              updateAppointment(event, revertFunc);
              

          },
          eventRender: function(event, element) {
            if(event.created_by == $('input[name="created_by"]').val())
            {
                element.append( "<span class='closeon fa fa-trash'></span>" );
                element.append( "<span class='appointment-details' ></span>" );
                element.find(".closeon").click(function() {
                   deleteAppointment(event._id, event);
                });
                
            }
            
            if (event.rendering == 'background') {
                element.append('<h3>'+ event.title + '</h3>');
            }
            element.append('<div data-createdby="'+ event.created_by +'"></div>');
            element.append('<div data-id="' + event.id +'"></span>' );
           
            if(event.patient_id && event.patient)
            {
              element.find(".appointment-details").popover({
                  title: 'Cita con el Dr. '+ event.user.name,
                  placement: 'top',
                  html:true,
                  container:'#calendar',
                  trigger: 'click focus', 
                  content: 'Fecha: '+ event.start.format("YYYY-MM-DD") +' <br>De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + '<br>Paciente: ' + event.patient.first_name + ' '+ event.patient.last_name,
              });
            }

        },
        
      });

    }

    function isOverlapping(event) {
     
        var array = $('#calendar').fullCalendar('clientEvents');
         
          for(i in array){
               if (event.end > array[i].start._i && event.start < array[i].end._i){
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

                if(resp.allDay)
                {
                  
                  deleteAppointment(resp.id);
                
                }else{
                    
                    $('#calendar').fullCalendar('renderEvent', resp, true);
                    
                    $('#myModal').modal({backdrop:'static', show:true });
                    $('#myModal').find('.btn-finalizar-cita').attr('data-appointment', resp.id).show();
                    $('#myModal').find('.btn-cancelar-cita').attr('data-appointment', resp.id).show();
                   
                   
                  }
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
                  $('#myModal').find('.btn-finalizar-cita').attr('data-appointment', '');
                  $('#myModal').find('.btn-cancelar-cita').attr('data-appointment', '');
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
                   
                   $('#calendar').fullCalendar( 'removeEvents', data.id)
                   
                    resp.allDay = parseInt(resp.allDay);
                   

                    $('#calendar').fullCalendar('renderEvent', resp, true);
                  
                  
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
        end : (event.end) ? event.end.format() : event.start.add(1, 'hours').format(),
        backgroundColor: event.backgroundColor, //Success (green)
        borderColor: event.borderColor,
        user_id: event.user_id,
        patient_id: (event.patient_id) ? event.patient_id : 0,
        created_by: event.created_by,
        idRemove: idRemove,
        allDay: 0
        
      };
     
      if(isOverlapping(appointment)){
        appointment.allDay = 1;
      }

      crud('POST', '/medics/appointments', appointment)

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
        created_by: event.created_by,
        id: event.id,
        allDay: 0
      };
      
      crud('PUT', '/medics/appointments/'+appointment.id, appointment, revertFunc)
    

    }

    function deleteAppointment(id)
    {

      crud('DELETE', '/medics/appointments/'+ id + '/delete', {idRemove:id})
     
    }

   

    /*$('#myModal').on('show.bs.modal', function (event) {
      //debugger
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-title').text('New message to ' + recipient)
      //modal.find('.modal-body').data('appointment','');
    });*/
   
   
     $('.btn-finalizar-cita').on('click', function (e) {
       
       var patient_id = $('#myModal').find('.widget-user-2').attr('data-patient');
       var appointment_id = $(this).attr('data-appointment'); //data('appointment');
       var title = $('#myModal').find('.widget-user-2').attr('data-title');
      
         
              $.ajax({
                  type: 'PUT',
                  url: '/medics/appointments/'+ appointment_id,
                  data: { patient_id : patient_id, title: 'Cita - '+ title  },
                  success: function (resp) {
                      console.log(resp);
                    
                      $('#myModal').modal('hide');
                      
                      $('#calendar').fullCalendar( 'removeEvents', resp.id)
                   
                      resp.allDay = parseInt(resp.allDay);
                     

                      $('#calendar').fullCalendar('renderEvent', resp, true);
                  },
                  error: function () {
                    console.log('error saving appointment');

                  }
             
             
           
            });
    });

    $('#myModal').on('click','.btn-cancelar-cita', function (e) {
       
       var appointment_id = $(this).attr('data-appointment');

              $.ajax({
                  type: 'DELETE',
                  url: '/medics/appointments/'+ appointment_id + '/delete',
                  data: { id : appointment_id  },
                  success: function (resp) {
                      console.log(resp);
                      $('#calendar').fullCalendar('removeEvents',appointment_id);
                      $('#myModal').modal('hide');
                  },
                  error: function () {
                    console.log('error delete appointment');

                  }
             
             
           
            });
    });




  });
