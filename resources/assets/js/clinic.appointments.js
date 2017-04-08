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
      $('.box-offices').hide();
    }else{
      //$('.box-create-appointment').show();
    }


    $(".dropdown-toggle").dropdown();
    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        var eventObject = {
          title: $.trim($(this).text()), // use the element's text as the event title
          office_id: $(this).data('office'),
          patient_id: $(this).data('patient'),
          start: moment(),
          end: moment(),
          backgroundColor: $(this).css("background-color"),
          borderColor: $(this).css("border-color")
          //created_by: $(this).data('createdby')
          
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('event', eventObject);
       
        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });
       
      });
    }
   

    ini_events($('#external-events div.external-event'));

    
    function fetch_events() {

        $.ajax({
            type: 'GET',
            url: '/clinic/appointments/list?medic='+ $('#calendar').data('medic'),
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
  
    var $calendar = $('#calendar');
   
  
  
      fetch_events();
    
   


    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();

     
     var minTime = $calendar.attr('data-minTime') ? $calendar.attr('data-minTime') : '06:00:00';
     var maxTime = $calendar.attr('data-maxTime') ? $calendar.attr('data-maxTime') : '18:00:00';
     var slotDuration = $('#selectSlotDuration').val() ? $('#selectSlotDuration').val() : $calendar.attr('data-slotDuration');
     var eventDurationNumber = (slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
     var eventDurationMinHours = (slotDuration.split(':')[1] == "00" ? 'hours' : 'minutes');
     var freeDays = $calendar.attr('data-freeDays') ? JSON.parse($calendar.attr('data-freeDays')) : [];
     var businessHours = [ 1, 2, 3, 4, 5, 6, 0];
    
      for(d in businessHours){
         for(f in freeDays){
                if(freeDays[f] == businessHours[d])
                {
                  var index = businessHours.indexOf(businessHours[d]);
                   if (index > -1) {
                      businessHours.splice(index, 1);
                   }
                 
                }
            }
      }
      
    

    function initCalendar(appointments)
    {
      
     
      $calendar.fullCalendar({
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
          slotDuration:  slotDuration,
          defaultTimedEventDuration: slotDuration,
          editable: true,
          droppable: true, // this allows things to be dropped onto the calendar !!!
          eventOverlap: false,
          businessHours: {
             // days of week. an array of zero-based day of week integers (0=Sunday)
              dow: businessHours,//[ 1, 2, 3, 4, 5, 6], // Monday - Thursday

              start: minTime, // a start time (10am in this example)
              end: maxTime, // an end time (6pm in this example)
          },
          eventConstraint:"businessHours",
          minTime: minTime,
          maxTime: maxTime,
          //selectable: true,
          //selectOverlap: false,
          //selectHelper: true,
          //weekends: false,
          scrollTime: minTime,
          nowIndicator: true,
          timezone: 'local',
          allDaySlot: false,
          select: function(start, end, jsEvent, view) {
            
            if (view.name === "month") {

                $('#calendar').fullCalendar('gotoDate', date);
                $('#calendar').fullCalendar('changeView', 'agendaWeek');

                return false;
            }
            
            var currentDate = new Date();
             
              
              if(start < currentDate || $(jsEvent.target).hasClass("fc-nonbusiness")) {
                    
                    $('#infoBox').addClass('alert-danger').html('Hora no permitida!. No puedes selecionar horas pasadas o fuera del horario de atención').show();
                      setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').html('').hide();
                        },3000);

                  $calendar.fullCalendar('unselect');
                  
                  return false;
              }


                $('#myModal').modal({backdrop:'static', show:true });
                $('#myModal').find('#modal-new-event').attr('data-modaldate', start.format());
                $('#myModal').find('#modal-new-event').attr('data-modaldate-end', end.format());
                $('#myModal').find('.modal-body').attr('data-modaldate', start.format());
                $('#myModal').find('.modal-body').attr('data-modaldate-end', end.format());
                $('#myModal').find('.modal-body').attr('data-date', start.format("dddd, MMMM Do YYYY")).attr('data-hour', start.format("hh:mm a" ));

               // $calendar.fullCalendar('unselect');
                
          },
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
            var originalEventObject = $(this).data('event');
          
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

           // var _id = $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)[0]._id; // get _id from event in the calendar (this is for if user will remove the event)
            
           /*if(prog_schedule)
              saveSchedule(copiedEventObject, _id);
            else
              saveAppointment(copiedEventObject, _id);*/
           
           /* if($(this).data('patient'))
              $(this).remove(); // remover de citas sin agendar*/
         
           
          },
           eventReceive: function(event) {
           
             var currentDate = new Date();
             if(event.start < currentDate) {
                   
                   $('#calendar').fullCalendar( 'removeEvents', event._id)
                   
                   return false;
              }

              saveAppointment(event, event._id);
            
          },
          eventResize: function(event, delta, revertFunc, jsEvent) {
             
      
                updateAppointment(event, revertFunc);
          
           
          },
          eventDrop: function(event, delta, revertFunc) {
             
           
                updateAppointment(event, revertFunc);
              

          },
          eventRender: function(event, element) {
            
            if(element.hasClass("fc-nonbusiness"))
            {
                $('#infoBox').addClass('alert-danger').html('Hora no permitida!. No puedes selecionar horas pasadas o fuera del horario de atención').show();
                      setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').html('').hide();
                        },3000);

                  return false;
            }
            

            //element.append( "<span class='closeon fa fa-trash'></span>" );
            element.append( "<span class='appointment-details' ></span>" );

            /*element.find(".closeon").click(function() {
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
               
            });*/
            if (event.rendering == 'background') {
                element.append('<span class="title-bg-event">'+ event.title + '</span>');
                
            }


             element.append('<div data-createdby="'+ event.created_by +'"></div>');

            if(event.patient_id && event.patient)
            {
              var officeInfoDisplay = '';

              if(event.office)
                {
                     var officeInfo = event.office;//JSON.parse(event.office_info);

                      officeInfoDisplay = 'en la '+ officeInfo.type +' '+ officeInfo.name+ ' <br>Dirección: ' + officeInfo.address + ', ' + officeInfo.province +', Tel: <a href="tel:'+ officeInfo.phone +'">'+ officeInfo.phone +'</a><br>'
                      
                   
                }
              element.find(".appointment-details").click(function() {

                  swal({
                    title: 'Cita con el Paciente '+ event.patient.first_name + ' '+ event.patient.last_name,
                    html: 'Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") +' <br>'+ officeInfoDisplay,
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Ok',
                    confirmButtonText: 'Eliminar cita'
                  }).then(function () {
                    
                    // if(prog_schedule)
                    //   deleteSchedule(event._id, event);
                    // else
                      var resp = deleteAppointment(event._id, event);
                      
                      if(resp){

                        swal(
                          'Cita cancelada!',
                          'Tu cita ha sido eliminada del calendario.',
                          'success'
                        )
                      }

                  },function (dismiss) {});

              });
             /* element.find(".appointment-details").popover({
                  title: 'Cita con el Dr. '+ event.user.name,
                  placement: 'top',
                  html:true,
                  container:'#calendar',
                  trigger: 'click focus', 
                  content: 'Fecha: '+ event.start.format("YYYY-MM-DD") +' <br>De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + '<br>Paciente: ' + event.patient.first_name + ' '+ event.patient.last_name,
              });*/

            }else{
        
                var officeInfoDisplay = '';
                var titleAlert = event.title;
                var textAlert = 'Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + officeInfoDisplay;

                if(event.office)
                {
                     var officeInfo = event.office;//JSON.parse(event.office_info);

                      officeInfoDisplay = '<br>Dirección: ' + officeInfo.address + ', ' + officeInfo.province +' <br>'
                      
                      titleAlert = 'Este horario está reservado para atención en la '+ officeInfo.type +' '+ officeInfo.name
                      
                      textAlert = 'Favor llamar a este número: '+ officeInfo.phone + ' <br> Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + officeInfoDisplay
                }
               
               
                element.find(".appointment-details").click(function() {
                   swal({
                      title: titleAlert,
                      html: textAlert,
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      cancelButtonText: 'Ok',
                      confirmButtonText: 'Eliminar!'
                    }).then(function () {

                      //if(prog_schedule)
                        deleteSchedule(event._id, event);
                      // else
                      //   deleteAppointment(event._id, event);

                      swal(
                        'Evento eliminado!',
                        'Tu evento ha sido eliminado del calendario.',
                        'success'
                      )

                    },function (dismiss) {});

                   
                  
                   
                });

                /*element.find(".appointment-details").popover({
                    title:  event.title,
                    placement: 'top',
                    html:true,
                    container:'#calendar',
                    trigger: 'click focus', 
                    content: 'Fecha: '+ event.start.format("YYYY-MM-DD") +' <br>De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + officeInfoDisplay,
                });*/
            }
            

        },
      
        dayClick: function(date, jsEvent, view) {

              
              if (view.name === "month") {
                  
                  $('#calendar').fullCalendar('gotoDate', date);
                  $('#calendar').fullCalendar('changeView', 'agendaWeek');

                  return false;
              }

              var currentDate = new Date();
              
          
              if(date < currentDate || $(jsEvent.target).hasClass("fc-nonbusiness")) {
                  
                  
                    $('#infoBox').addClass('alert-danger').html('Hora no permitida!. No puedes selecionar horas pasadas o fuera del horario de atención').show();
                      setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').html('').hide();
                        },3000);

                  return false;
              }

             
               if($(jsEvent.target).parent('div').hasClass("fc-bgevent")) { //para prevenir que en eventos de fondo se agregue citas
                  

                  return false;
              }


           

             

                $('#myModal').modal({backdrop:'static', show:true });
                $('#myModal').find('#modal-new-event').attr('data-modaldate', date.format());
                $('#myModal').find('.modal-body').attr('data-modaldate', date.format());
                $('#myModal').find('.modal-body').attr('data-date', date.format("dddd, MMMM Do YYYY")).attr('data-hour', date.format("hh:mm a" ));
              
              
           
          }
        
      });

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
                
                if(resp){

                      
                      resp.allDay = parseInt(resp.allDay);

                      if(resp.allDay)
                      {
                        if(prog_schedule)
                          deleteSchedule(resp.id);
                        else
                          deleteAppointment(resp.id);
                      
                      }else{
                          
                          $('#calendar').fullCalendar('renderEvent', resp, true);

                        }
                }else{
                  $('#infoBox').addClass('alert-danger').html('No se pudo crear la consulta!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

                   return
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

                   return resp;
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
        office_id: event.office_id,
        patient_id: (event.patient_id) ? event.patient_id : 0,
        user_id: event.user_id,
        idRemove: idRemove,
        office_info: (event.office_info) ? event.office_info : '',
        allDay: 0
        
      };
     
      if(isOverlapping(appointment)){
        appointment.allDay = 1;
      }

      crud('POST', '/clinic/appointments', appointment)

    }

    

     function updateAppointment(event, revertFunc)
    {
      
      var appointment = {
        //title : event.title,
        date : event.start.format("YYYY-MM-DD"),
        start : event.start.stripZone().format(),
        end : (event.end) ? event.end.stripZone().format() : event.start.add(eventDurationNumber, eventDurationMinHours).stripZone().format(),
        //backgroundColor: event.backgroundColor, //Success (green)
        //borderColor: event.borderColor,
        //user_id: event.user_id,
        patient_id: event.patient_id,
        //created_by: event.created_by,
        id: event.id,
        office_info: event.office_info,
        allDay: event.allDay
      };
      
      crud('PUT', '/clinic/appointments/'+appointment.id, appointment, revertFunc)

    }

    function deleteAppointment(id)
    {

      crud('DELETE', '/clinic/appointments/'+ id + '/delete', {idRemove:id})
     
    }

   

   

    function createEventFromModal()
    {
     
      var currColor = "#3c8dbc"; //Red by default
      var val = $("#modal-new-event").val();
      var valSelect = $(".modal-body").find('.widget-user-2').attr('data-patient');//val();
      var valName = $(".modal-body").find('.widget-user-2').attr('data-title');
      var office_id = $(".modal-body").find('.widget-user-2').attr('data-office');
      var medic_id = $("#calendar").attr('data-medic');
      var date = $.fullCalendar.moment($('#modal-new-event').attr('data-modaldate'));
      var end = ($('#modal-new-event').attr('data-modaldate-end')) ? $.fullCalendar.moment($('#modal-new-event').attr('data-modaldate-end')) : '';
     
      if (valSelect.length == 0 || !office_id) {
        return;
      }
     

      //Create events

      var eventObject = {
          title: $.trim(val + ' - '+ valName), // use the element's text as the event title
          user_id: medic_id,
          patient_id: valSelect,
          office_id: office_id,
          //created_by: $('input[name=user_id]').val()
         
        };

       var originalEventObject = eventObject;//event.data('eventObject');
        
        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);
        
        
        // assign it the date that was reported
        copiedEventObject.start = date;
        
        if(end)
          copiedEventObject.end = end;

        if( date.isValid())
        {
         
        
        copiedEventObject.allDay = false;//allDay;
        copiedEventObject.backgroundColor = currColor; //event.css("background-color");
        copiedEventObject.borderColor = currColor;//event.css("border-color");
        copiedEventObject.overlap = false;
        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)

        var _id = $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)[0]._id; // get _id from event in the calendar (this is for if user will remove the event)
       
       
        saveAppointment(copiedEventObject, _id);
      }
      //Remove event from text input
      $("#modal-new-event").val("");
      //$(".modal-search-patients").val("").trigger('change');
      //$(".modal-search-patients").text("").trigger('change');
      $('#myModal').find('#modal-new-event').attr('data-modaldate', '');
      $('#myModal').modal('hide');
    }

     $(".btn-finalizar-cita").click(function (e) {
      e.preventDefault();

      createEventFromModal();
      
    });
   $(".modal-search-patients").select2({
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
               $(".modal-search-patients").empty();
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


    $('#myModal').on('shown.bs.modal', function (event) {
     
      var date = $(this).find('.modal-body').attr('data-date') 
      var hour = $(this).find('.modal-body').attr('data-hour')
     
  
      var modal = $(this)
      modal.find('.modal-title').html('Crear cita para el  <span class="label bg-yellow">' + date + '</span> a las <span class="label bg-yellow">'+ hour + '</span>' )
   
    });


   

    

     

      






  });