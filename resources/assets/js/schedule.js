$(function () {

    var isMobile = {
      Android: function() {
          return navigator.userAgent.match(/Android/i);
      },
      BlackBerry: function() {
          return navigator.userAgent.match(/BlackBerry/i);
      },
      iOS: function() {
          return navigator.userAgent.match(/iPhone|iPad|iPod/i);
      },
      Opera: function() {
          return navigator.userAgent.match(/Opera Mini/i);
      },
      Windows: function() {
          return navigator.userAgent.match(/IEMobile/i);
      },
      any: function() {
          return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
      }
  };

  if( isMobile.any() ) {
      $('.box-create-appointment').hide();
    }else{
      //$('.box-create-appointment').show();
    }

    $(".dropdown-toggle").dropdown();
    
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
          patient_id: $(this).data('patient')
          //created_by: $(this).data('createdby')
         
          
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
            url: '/medics/'+ $('.external-event').data('doctor') +'/appointments/list',
            data: {},
            success: function (resp) {
                console.log(resp);

                var appointments = [];

                $.each(resp, function( index, item ) {
                   
                    item.allDay = parseInt(item.allDay); // = false;
                    
                    if(item.patient_id != 0 && item.created_by != $('.external-event').data('createdby')){
                       //item.rendering = 'background';
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

    var $calendar = $('#calendar');
     var slotDuration = $calendar.attr('data-slotDuration') ? $calendar.attr('data-slotDuration') : '00:30:00';
     var minTime = $calendar.attr('data-minTime') ? $calendar.attr('data-minTime') : '06:00:00';
     var maxTime = $calendar.attr('data-maxTime') ? $calendar.attr('data-maxTime') : '18:00:00';
     var eventDurationNumber = (slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
     var eventDurationMinHours = (slotDuration.split(':')[1] == "00" ? 'hours' : 'minutes');

    function initCalendar(appointments)
    {

      $calendar.fullCalendar({
          locale: 'es',
          defaultView: 'agendaWeek',
          timeFormat: 'h(:mm)a',
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaWeek,agendaDay'
          },
          //Random default events
          events: appointments,
          forceEventDuration: true,
          slotDuration: slotDuration,
          defaultTimedEventDuration: slotDuration,
          editable: false,
          droppable: true, // this allows things to be dropped onto the calendar !!!
          eventOverlap: false,
          businessHours: {
              // days of week. an array of zero-based day of week integers (0=Sunday)
              dow: [ 1, 2, 3, 4, 5,6], // Monday - Thursday

              start: minTime, // a start time (10am in this example)
              end: maxTime, // an end time (6pm in this example)
          },
          minTime: minTime,
          maxTime: maxTime,
          scrollTime: minTime,
          nowIndicator: true,
          timezone: 'local',
          allDaySlot: false,
          drop: function (date, allDay) { // this function is called when something is dropped

            var currentDate = new Date();
            
            if(date < currentDate) {

                  $('#infoBox').addClass('alert-danger').html('Hora no permitida!. No puedes selecionar horas pasadas o fuera del horario de atención').show();
                      setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').html('').hide();
                        },3000);

                   return false;
              }
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
            element.append( "<span class='appointment-details' ></span>" );

            /*if(event.created_by == $('.external-event').data('createdby'))
            {
                element.append( "<span class='closeon fa fa-trash'></span>" );
                
                element.find(".closeon").click(function() {
                  swal({
                      title: "Deseas cancelar la cita?",
                      text: " Requerda que se eliminara del sistema!",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonClass: "btn-danger",
                      confirmButtonText: "Si, Cancelar!",
                      closeOnConfirm: false
                    },
                    function(){
                      deleteAppointment(event._id, event);
                      swal("Cita cancelada!", "Tu cita ha sido eliminada.", "success");
                    });
                  
                   
                });
                
            }**/
            
            if (event.rendering == 'background') {
                element.append('<h3>'+ event.title + '</h3>');
            }
            element.append('<div data-createdby="'+ event.created_by +'"></div>');
            element.append('<div data-id="' + event.id +'"></span>' );
           
            if(event.patient_id && event.patient)
            {
              if(event.created_by == $('.external-event').data('createdby'))
              {
                element.find(".appointment-details").click(function() {
                    
                     swal({
                      title: 'Cita con el Paciente '+ event.patient.first_name + ' '+ event.patient.last_name,
                      text: 'Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm"),
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      cancelButtonText: 'Ok',
                      confirmButtonText: 'Eliminar!'
                    }).then(function () {
                      
                      deleteAppointment(event._id, event);

                      swal(
                        'Cita cancelada!',
                        'Tu cita ha sido eliminada del calendario.',
                        'success'
                      )

                    },function (dismiss) {});

                   
                     
                  });
              }
              

            }else{

                var officeInfoDisplay = '';
                var titleAlert = event.title;
                var textAlert = 'Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + officeInfoDisplay;


                if(event.office_info)
                {
                     var officeInfo = JSON.parse(event.office_info);

                      officeInfoDisplay = '<br>Dirección: ' + officeInfo.address + ', ' + officeInfo.province +' <br>';

                      titleAlert = 'Este horario está reservado para atención en la '+ officeInfo.type +' '+ event.title
                      
                      textAlert = 'Favor llamar a este número: <a href="tel:'+ officeInfo.phone +'">'+ officeInfo.phone +'</a> <br>Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + officeInfoDisplay
                }
                element.find(".appointment-details").click(function() {
                   
                    swal({
                        title: titleAlert,
                        html: textAlert
                        
                      });
                  
                  
                   
                });
                
              
            }

        },
        dayClick: function(date, jsEvent, view) {
              var currentDate = new Date();

              if(date < currentDate || $(jsEvent.target).hasClass("fc-nonbusiness")) {

                   $('#infoBox').addClass('alert-danger').html('Hora no permitida!. No puedes selecionar horas pasadas o fuera del horario de atención').show();
                      setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').html('').hide();
                        },3000);

                   return false;
              }

              if($calendar.attr('data-appointmentsday') >= 2)
                 {
                  $('#infoBox').addClass('alert-danger').html('No se puede crear más de dos citas al dia!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

                   return false;
                  }
          

              $('#myModal').modal({backdrop:'static', show:true });
              $('#myModal').find('.modal-body').attr('data-modaldate', date.format());
              $('#myModal').find('.modal-body').attr('data-date', date.format("dddd, MMMM Do YYYY")).attr('data-hour', date.format("hh:mm a" ));
                    
           
          }
        
      });

      $('#calendar').fullCalendar('today');
    }

    function isOverlapping(event) {
     
        var array = $('#calendar').fullCalendar('clientEvents');
         
          for(i in array){
            if(event.idRemove != array[i]._id)
              {
                 if (event.end > array[i].start._i && event.start < array[i].end._i){
                   return true;
                }
              }
          }
          return false;
    }
    
    /* SAVE UPDATE DELETE EVENTS */
    function crud(method, url, data, revertFunc) {
      $('body').addClass('loading');
     
      $.ajax({
            type: method || 'POST',
            url: url,
            data: data,
            success: function (resp) {
              $('body').removeClass('loading');
              
              if(method == "POST")
              {
                $('#calendar').fullCalendar( 'removeEvents', data.idRemove)
                
                if(resp == 'max-per-day')
                 {
                  $('#infoBox').addClass('alert-danger').html('No se puede crear más de dos citas al dia!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

                   return
                  }
                  /*if(isOverlapping(resp))
                    resp.allDay = 1; // si se montan poner el evento en todo el dia*/
                
                resp.appointment.allDay = parseInt(resp.appointment.allDay);

                if(resp.appointment.allDay)
                {
                  
                  deleteAppointment(resp.appointment.id);
                
                }else{
                    
                    $('#calendar').fullCalendar('renderEvent', resp.appointment, true);
                    $('#calendar').attr('data-appointmentsday', resp.appointmentsToday);
                    
                    $('#modalReminder').modal({backdrop:'static', show:true });
                    $('#modalReminder').find('.modal-body').attr('data-appointment', resp.appointment.id).show();
                    
         
                   
                   
                  }
              }
               if(method == "DELETE")
               {
                
                 if(resp.resp == 'error')
                 {
                  $('#infoBox').addClass('alert-danger').html('No se puede eliminar consulta ya que se encuentra iniciada!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

                   return
                  }

                  $('#calendar').fullCalendar('removeEvents',data.idRemove);
                  $('#calendar').attr('data-appointmentsday', resp.appointmentsToday);
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
               $('body').removeClass('loading');
              console.log('error saving appointment');

            }
        });
    }
    
    function saveAppointment(event, idRemove)
    {
      
      var appointment = {
        title : event.title,
        date : event.start.format("YYYY-MM-DD"),
        start : event.start.stripZone().format(),
        end : (event.end) ? event.end.stripZone().format() : event.start.add(eventDurationNumber, eventDurationMinHours).stripZone().format(),
        backgroundColor: event.backgroundColor, //Success (green)
        borderColor: event.borderColor,
        user_id: event.user_id,
        patient_id: (event.patient_id) ? event.patient_id : 0,
        /*created_by: event.created_by,*/
        idRemove: idRemove,
        office_info: (event.office_info) ? event.office_info : '',
        allDay: 0
        
      };
     
      if(isOverlapping(appointment)){
        appointment.allDay = 1;
      }

      crud('POST', '/appointments', appointment)

    }

     function updateAppointment(event, revertFunc)
    {
      
      var appointment = {
        title : event.title,
        date : event.start.format("YYYY-MM-DD"),
        start : event.start.stripZone().format(),
        end : (event.end) ? event.end.stripZone().format() : event.start.add(eventDurationNumber, eventDurationMinHours).stripZone().format(),
        //backgroundColor: event.backgroundColor, //Success (green)
       // borderColor: event.borderColor,
        //user_id: event.user_id,
        patient_id: event.patient_id,
        //created_by: event.created_by,
        id: event.id,
        office_info: event.office_info,
        allDay: event.allDay
      };
      
      crud('PUT', '/appointments/'+appointment.id, appointment, revertFunc)
    

    }

    function deleteAppointment(id)
    {

      crud('DELETE', '/appointments/'+ id + '/delete', {idRemove:id})
     
    }

   

    $('#myModal').on('shown.bs.modal', function (event) {
      //debugger
     // var button = $(event.relatedTarget) // Button that triggered the modal
      var date = $(this).find('.modal-body').attr('data-date') // Extract info from data-* attributes
      var hour = $(this).find('.modal-body').attr('data-hour')
     
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-title').html('Su cita se programará para el  <span class="label bg-yellow">' + date + '</span> a las <span class="label bg-yellow">'+ hour + '</span>' )
      //modal.find('.modal-body').data('appointment','');
    });


     function createEventFromModal()
    {
              var event = $('div.external-event');
              var date = $.fullCalendar.moment($('.modal-body').attr('data-modaldate'));
              var patient_id = $('#myModal').find('.widget-user-2').attr('data-patient');
              var eventObject = {
                title: $.trim(event.text()), // use the element's text as the event title
                user_id: event.data('doctor'),
                patient_id: patient_id
                //created_by: event.data('createdby')
                
              };
              
              var originalEventObject = eventObject;
          
              // we need to copy it, so that multiple events don't have a reference to the same object
              var copiedEventObject = $.extend({}, originalEventObject);
              
              // assign it the date that was reported
              copiedEventObject.start = date;

               if( date.isValid())
                {
                 
                
                    copiedEventObject.allDay = false;//allDay;
                    copiedEventObject.backgroundColor = event.css("background-color");
                    copiedEventObject.borderColor = event.css("border-color");
                    copiedEventObject.overlap = false;
                    
                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)

                    var _id = $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)[0]._id; // get _id from event in the calendar (this is for if user will remove the event)
                    
                   
                    saveAppointment(copiedEventObject, _id);
              }
              //Remove event from text input
              
              $('#myModal').find('.modal-body').attr('data-modaldate', '');
              $('#myModal').modal('hide');
             
              
              
    }

    /* $("#modal-add-new-event").click(function (e) {
        e.preventDefault();

        createEventFromModal();
        
      });
   */
   
     $('.btn-finalizar-cita').on('click', function (e) {
       e.preventDefault();
        createEventFromModal();
       
    });

      $('.btn-finalizar-reminder').on('click', function (e) {
         e.preventDefault();
          
          var id = $('#modalReminder').find('.modal-body').attr('data-appointment');
          var reminder_time = $('#modalReminder').find('#reminder_time').val();
          $('body').addClass('loading');

           $.ajax({
              type: 'POST',
              url: '/appointments/'+ id + '/reminder',
              data: { reminder_time : reminder_time },
              success: function (resp) {

                $('body').removeClass('loading');

                $('#modalReminder').modal('hide');
              },
              error: function () {
                
                $('body').removeClass('loading');

                console.log('error saving reminder');
                
                $('#modalReminder').modal('hide');

              }
          });
       
    });

  



  });
