$(function () {

     
     var slotDuration = '00:30:00';
     var minTime = '06:00:00';
     var maxTime ='18:00:00';
     var eventDurationNumber = (slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
     var eventDurationMinHours = (slotDuration.split(':')[1] == "00" ? 'hours' : 'minutes');
     var freeDays = [0];
     var businessHours = [ 1, 2, 3, 4, 5, 6, 0];
     var calendar = $('#calendar');
     var externalEvent = $('.external-event');
     var infoBox = $('#infoBox');
     var modalForm = $('#myModal');
     var modalReminder = $('#modalReminder');

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


      function isOverlapping(event) {
     
        var array = calendar.fullCalendar('clientEvents');
         
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

       function dayNumber(date){
         
          return $.fullCalendar.moment(date).day();
      }

      ini_events($('#external-events div.external-event'));

      function ini_events(ele) {
     
      ele.each(function () {

        var eventObject = {
          title: $.trim($(this).text()), // use the element's text as the event title
          user_id: $(this).data('doctor'),
          patient_id: $(this).data('patient'),
          office_id: $(this).data('office'),
          start: moment(),
          end: moment()
         
          
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


    } //init events

    fetch_schedules_and_appointments();

    function fetch_schedules_and_appointments() {
        var schedules = [];

        $.ajax({
            type: 'GET',
            url: '/medics/'+ externalEvent.data('doctor') +'/schedules/list?office='+ externalEvent.data('office'),
            data: {},
            success: function (resp) {
              

                $.each(resp, function( index, item ) {
                   
                    item.allDay = parseInt(item.allDay); // = false;
                    //item.rendering = 'background';
                     
                     var working_hours = {
                      // days of week. an array of zero-based day of week integers (0=Sunday)
                      dow: [dayNumber(item.date)], // Monday - Thursday

                      start: item.start,//.split('T')[1], // a start time (10am in this example)
                      end: item.end//.split('T')[1], // an end time (6pm in this example)
                     }

                    schedules.push(working_hours);

                });
                
                //initCalendar(schedules,schedules);

                $.ajax({
                  type: 'GET',
                  url: '/medics/'+ externalEvent.data('doctor') +'/appointments/list?office='+ externalEvent.data('office'),
                  data: {},
                  success: function (resp) {
                     

                      var appointments = [];

                      $.each(resp, function( index, item ) {
                         
                          item.allDay = parseInt(item.allDay); // = false;
                          
                          if((item.patient_id != 0 && item.created_by != externalEvent.data('createdby')) || item.patient_id == 0){
                             item.rendering = 'background';
                          }
                          

                          appointments.push(item);
                      });
                     
                      initCalendar(appointments,schedules);
                      
                  },
                  error: function (resp) {
                      console.log('Error - '+ resp);

                  }
              });
               
                //initCalendar(schedules);
                
            },
            error: function (resp) {
                console.log('Error - '+ resp);

            }
        });


    } // fetch appointments and schedules

    

    function initCalendar(appointments,schedules)
    {

      calendar.fullCalendar({
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
          businessHours: schedules,
          eventConstraint:"businessHours",
          minTime: minTime,
          maxTime: maxTime,
          scrollTime: minTime,
          nowIndicator: true,
          timezone: 'local',
          allDaySlot: false,
          drop: function (date, jsEvent, ui,resourceId) { // this function is called when something is dropped
           
            var currentDate = new Date();
            
            if(date < currentDate) {

                  infoBox.addClass('alert-danger').html('Hora no permitida!. No puedes selecionar horas pasadas o fuera del horario de atención').show();
                      setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').html('').hide();
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
            
           
          },
          eventReceive: function(event) {
            
             var currentDate = new Date();
             if(event.start < currentDate) {
                   
                   calendar.fullCalendar( 'removeEvents', event._id)
                   
                   return false;
              }

            saveAppointment(event, event._id);
          },
          eventResize: function(event, delta, revertFunc) {


              updateAppointment(event, revertFunc);
          
           
          },
          eventDrop: function(event, delta, revertFunc) {
            
              updateAppointment(event, revertFunc);
              

          },
          eventRender: function(event, element) {
           
            element.append( "<span class='appointment-details' ></span>" );

            if (event.rendering == 'background') {
                element.append('<span class="title-bg-event">'+ event.title + '</span>');
                
            }
            element.append('<div data-createdby="'+ event.created_by +'"></div>');
            element.append('<div data-id="' + event.id +'"></span>' );
           
            if(event.patient_id && event.patient)
            {
              var officeInfoDisplay = '';
               if(event.office)
                {
                     var officeInfo = event.office;//JSON.parse(event.office_info);

                      officeInfoDisplay = 'en la '+ officeInfo.type +' '+ officeInfo.name + ' <br>Dirección: ' + officeInfo.address + ', ' + officeInfo.province +', Tel: <a href="tel:'+ officeInfo.phone +'">'+ officeInfo.phone +'</a><br>'
                      
                   
                }
              if(event.created_by == $('.external-event').data('createdby'))
              {
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
              }
              

            }else{

                var officeInfoDisplay = '';
                var titleAlert = event.title;
                var textAlert = 'Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + officeInfoDisplay;


                if(event.office)
                {
                     var officeInfo = event.office;//JSON.parse(event.office);

                      officeInfoDisplay = '<br>Dirección: ' + officeInfo.address + ', ' + officeInfo.province +' <br>';

                      titleAlert = 'Este horario está reservado para atención en la '+ officeInfo.type +' '+ officeInfo.name
                      
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

                   infoBox.addClass('alert-danger').html('Hora no permitida!. No puedes selecionar horas pasadas o fuera del horario de atención').show();
                      setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').html('').hide();
                        },3000);

                   return false;
              }

               if($(jsEvent.target).parent('div').hasClass("fc-bgevent")) { //para prevenir que en eventos de fondo se agregue citas
                  

                  return false;
              }

              if(calendar.attr('data-appointmentsday') >= 2)
                 {
                  infoBox.addClass('alert-danger').html('No se puede crear más de dos citas al dia!!').show();
                        setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').hide();
                        },3000);

                   return false;
                  }
          

              modalForm.modal({backdrop:'static', show:true });
              modalForm.find('.modal-body').attr('data-modaldate', date.format());
              modalForm.find('.modal-body').attr('data-date', date.format("dddd, MMMM Do YYYY")).attr('data-hour', date.format("hh:mm a" ));
                    
           
          },
          viewRender: function(view){
            
            $.ajax({
              type: 'GET',
              url: '/medics/'+ externalEvent.data('doctor') +'/schedules/list?office='+ externalEvent.data('office'),
              data: {},
              success: function (resp) {
                
                  var schedules = [];
                  $.each(resp, function( index, item ) {
                     
                      item.allDay = parseInt(item.allDay); // = false;
                     
                       
                       var working_hours = {
                        // days of week. an array of zero-based day of week integers (0=Sunday)
                        dow: [dayNumber(item.date)], // Monday - Thursday

                        start: item.start,//.split('T')[1], // a start time (10am in this example)
                        end: item.end//.split('T')[1], // an end time (6pm in this example)
                       }

                      schedules.push(working_hours);

                  });

                  var bh = schedules;//$('#calendar').fullCalendar('option', 'businessHours');

                 
                    for (var i = bh.length - 1; i >= 0; i--) {

                      if(moment(bh[i].start).isBetween(view.start, view.end))
                      {
                        bh[i].start = bh[i].start.split('T')[1];
                        bh[i].end = bh[i].end.split('T')[1];
                      }

                    }
                   
                   
                    calendar.fullCalendar('option', 'businessHours', bh);


              },
            error: function (resp) {
                console.log('Error - '+ resp);

            }

          }); //ajax

           
            
          } //view render
        
        
      }); //fullcalendar

      calendar.fullCalendar('today');
      
    } //init calendar


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
                calendar.fullCalendar( 'removeEvents', data.idRemove)
                
                if(resp == 'max-per-day')
                 {
                  infoBox.addClass('alert-danger').html('No se puede crear más de dos citas al dia!!').show();
                        setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').hide();
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
                    
                    calendar.fullCalendar('renderEvent', resp.appointment, true);
                    calendar.attr('data-appointmentsday', resp.appointmentsToday);
                    
                    modalReminder.modal({backdrop:'static', show:true });
                    modalReminder.find('.modal-body').attr('data-appointment', resp.appointment.id).show();
                    
         
                   
                   
                  }
              }
               if(method == "DELETE")
               {
                 
                 if(resp.resp == 'error')
                 {
                  infoBox.addClass('alert-danger').html('No se puede eliminar consulta ya que se encuentra iniciada!!').show();
                        setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').hide();
                        },3000);

                  return resp.resp;
                  }

                  calendar.fullCalendar('removeEvents',data.idRemove);
                  calendar.attr('data-appointmentsday', resp.appointmentsToday);
                  modalForm.find('.btn-finalizar-cita').attr('data-appointment', '');
                  modalForm.find('.btn-cancelar-cita').attr('data-appointment', '');


               }
               
               if(method == "PUT")
               {
                 if(resp == '')
                 {
                  infoBox.addClass('alert-danger').html('No se puede cambiar de dia la consulta ya que se encuentra iniciada!!').show();
                        setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').hide();
                        },3000);


                    revertFunc();
                    
                   return
                  }
                   
                   calendar.fullCalendar( 'removeEvents', data.id)
                   
                    resp.allDay = parseInt(resp.allDay);
                   

                    calendar.fullCalendar('renderEvent', resp, true);
                  
                  
               }
                
            },
            error: function () {
               $('body').removeClass('loading');
              console.log('error saving appointment');

            }
        });
    } //CRUD

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
        office_id: (event.office_id) ? event.office_id : 0,
        idRemove: idRemove,
        office_info: (event.office_info) ? event.office_info : '',
        allDay: 0
        
      };
     
      if(isOverlapping(appointment)){
        appointment.allDay = 1;
      }

      crud('POST', '/appointments', appointment)

    } //save appointment

     function updateAppointment(event, revertFunc)
    {
      
      var appointment = {
        title : event.title,
        date : event.start.format("YYYY-MM-DD"),
        start : event.start.stripZone().format(),
        end : (event.end) ? event.end.stripZone().format() : event.start.add(eventDurationNumber, eventDurationMinHours).stripZone().format(),
        patient_id: event.patient_id,
        office_id: event.office_id,
        id: event.id,
        office_info: event.office_info,
        allDay: event.allDay
      };
      
      crud('PUT', '/appointments/'+appointment.id, appointment, revertFunc)
    

    } // update appointment

    function deleteAppointment(id)
    {

      crud('DELETE', '/appointments/'+ id + '/delete', {idRemove:id})
     
    }// delete appointments


    modalForm.on('shown.bs.modal', function (event) {
      //
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
              var date = $.fullCalendar.moment(modalForm.find('.modal-body').attr('data-modaldate'));
              var patient_id = modalForm.find('.widget-user-2').attr('data-patient');
              var eventObject = {
                title: $.trim(event.text()), // use the element's text as the event title
                user_id: event.data('doctor'),
                patient_id: patient_id,
                office_id: event.data('office')
                
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
                    
                    var _id = calendar.fullCalendar('renderEvent', copiedEventObject, true)[0]._id; // get _id from event in the calendar (this is for if user will remove the event)
                    
                   
                    saveAppointment(copiedEventObject, _id);
              }
              //Remove event from text input
              
              modalForm.find('.modal-body').attr('data-modaldate', '');
              modalForm.modal('hide');
             
              
              
    } //create from modal

     $('.btn-finalizar-reminder').on('click', function (e) {
         e.preventDefault();
          
          var id = modalReminder.find('.modal-body').attr('data-appointment');
          var reminder_time = modalReminder.find('#reminder_time').val();
          $('body').addClass('loading');

           $.ajax({
              type: 'POST',
              url: '/appointments/'+ id + '/reminder',
              data: { reminder_time : reminder_time },
              success: function (resp) {

                $('body').removeClass('loading');

                modalReminder.modal('hide');
              },
              error: function () {
                
                $('body').removeClass('loading');

                console.log('error saving reminder');
                
                modalReminder.modal('hide');

              }
          });
       
    });

    $('.btn-finalizar-cita').on('click', function (e) {
       e.preventDefault();
        createEventFromModal();
       
    });



});
