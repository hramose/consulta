$(function () {

     var minTime = '06:00:00';
     var maxTime = '18:00:00';
     var slotDuration = '00:30';
     var eventDurationNumber = moment.duration(slotDuration).asMinutes(); //(slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
     var eventDurationMinHours = 'minutes'; //(slotDuration.split(':')[1] == "00" ? 'hours' : 'minutes');
     var freeDays = [];
     var businessHours = [ 1, 2, 3, 4, 5, 6, 0];
     var infoBox = $('#infoBox');
     var modalForm = $('#myModal');
     var searchPatients = $(".modal-search-patients");

     //Initialize Select2 Elements
    $('.date').datetimepicker({
            format:'YYYY-MM-DD',
            locale: 'es',
            
         });
   
 
   $('.btn-gotodate').on('click', function (e) {
       var mid = $(this).data('mid');
       var date = $('#go_date_'+ mid).val();
       $('#calendar-m'+ mid).fullCalendar( 'gotoDate', date );
    })


    function dayNumber(date){

        return $.fullCalendar.moment(date).day();
    }

    function isOverlapping(calendar, event) {
     
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

    init_schedules_appointments($('td.calendar-medic-day'));
    
    function init_schedules_appointments(ele) {

            ele.each(function () {

              var medic =  $(this).find('> div').data('medic');
              var office =  $(this).find('> div').data('office');
              
             // fetch_schedules_and_appointments(medic,office);
             init_calendar(medic, [], []);
             
            });
    }

    /*function fetch_schedules_and_appointments(medic, office) {
        var schedules = [];
         
        $.ajax({
            type: 'GET',
            url: '/medics/'+ medic +'/schedules/list?office='+ office,
            data: {},
            success: function (resp) {
              

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
                
              

                $.ajax({
                  type: 'GET',
                  url: '/medics/'+ medic +'/appointments/list?office='+ office,
                  data: {},
                  success: function (resp) {
                     

                      var appointments = [];

                      $.each(resp, function( index, item ) {
                         
                          item.allDay = parseInt(item.allDay); // = false;
                          
                          if((item.patient_id != 0 && item.office_id != office) || item.patient_id == 0){
                             item.rendering = 'background';
                          }
                          

                          appointments.push(item);
                      });

                      init_calendar(medic, appointments, schedules);
                     
                      
                  },
                  error: function (resp) {
                      console.log('Error - '+ resp);

                  }
              });
               
          
            },
            error: function (resp) {
                console.log('Error - '+ resp);

            }
        });

    }*/

    function init_calendar(medic,appointments, schedules) {
     
     var calendar = $('#calendar-m'+medic);
     
      minTime = calendar.attr('data-minTime') ? calendar.attr('data-minTime') : '06:00:00';
      maxTime = calendar.attr('data-maxTime') ? calendar.attr('data-maxTime') : '18:00:00';
      slotDuration = $('#selectSlotDuration').val() ? $('#selectSlotDuration').val() : calendar.attr('data-slotDuration');
      eventDurationNumber = moment.duration(slotDuration).asMinutes(); //(slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
      eventDurationMinHours = 'minutes'; //(slotDuration.split(':')[1] == "00" ? 'hours' : 'minutes');
      freeDays = calendar.attr('data-freeDays') ? JSON.parse(calendar.attr('data-freeDays')) : [];
      
      
      calendar.fullCalendar({
          locale: 'es',
          defaultView: 'agendaDay',
          timeFormat: 'h(:mm)a',
          events: appointments,
          forceEventDuration: true,
          slotDuration:  slotDuration,
          defaultTimedEventDuration: slotDuration,
          editable: true,
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
          eventReceive: function(event) {
           
             var currentDate = new Date();
             if(event.start < currentDate) {
                   
                   calendar.fullCalendar( 'removeEvents', event._id)
                   
                   return false;
              }

              saveAppointment(calendar, event, event._id);
            
          },
          eventResize: function(event, delta, revertFunc, jsEvent) {
             
      
                updateAppointment(calendar, event, revertFunc);
          
           
          },
          eventDrop: function(event, delta, revertFunc) {
             
           
                updateAppointment(calendar, event, revertFunc);
              

          },
          eventRender: function(event, element) {
            
            if(element.hasClass("fc-nonbusiness"))
            {
                infoBox.addClass('alert-danger').html('Hora no permitida!. No puedes selecionar horas pasadas o fuera del horario de atención').show();
                      setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').html('').hide();
                        },3000);

                  return false;
            }
            
            element.append( "<span class='appointment-details' ></span>" );

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

                      officeInfoDisplay = 'en '+ officeInfo.type +' '+ officeInfo.name+ ' <br>Dirección: ' + officeInfo.address + ', ' + officeInfo.province +', Tel: <a href="tel:'+ officeInfo.phone +'">'+ officeInfo.phone +'</a><br>'
                      
                   
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
                    
                    
                      var resp = deleteAppointment(calendar, event._id, event);
                      
                      if(resp){

                        swal(
                          'Cita cancelada!',
                          'Tu cita ha sido eliminada del calendario.',
                          'success'
                        )
                      }

                  },function (dismiss) {});

              });
             

            }else{
        
                var officeInfoDisplay = '';
                var titleAlert = event.title;
                var textAlert = 'Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm") + officeInfoDisplay;

                if(event.office)
                {
                     var officeInfo = event.office;//JSON.parse(event.office_info);

                      officeInfoDisplay = '<br>Dirección: ' + officeInfo.address + ', ' + officeInfo.province +' <br>'
                      
                      titleAlert = 'Este horario está reservado para atención en '+ officeInfo.type +' '+ officeInfo.name
                      
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

                      
                        deleteSchedule(event._id, event);
                     

                      swal(
                        'Evento eliminado!',
                        'Tu evento ha sido eliminado del calendario.',
                        'success'
                      )

                    },function (dismiss) {});

                   
                  
                   
                });

              
            }
            

        },
      
        dayClick: function(date, jsEvent, view) {

              
              if (view.name === "month") {
                  
                  calendar.fullCalendar('gotoDate', date);
                  calendar.fullCalendar('changeView', 'agendaWeek');

                  return false;
              }

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


           

             

                modalForm.modal({backdrop:'static', show:true });
                modalForm.find('#modal-new-event').attr('data-modaldate', date.format());
                modalForm.find('.modal-body').attr('data-modaldate', date.format());
                modalForm.find('.modal-body').attr('data-date', date.format("dddd, MMMM Do YYYY")).attr('data-hour', date.format("hh:mm a" ));
                modalForm.find('.modal-body').attr('data-medic', calendar.data('medic'));
              
              
           
          },
        viewRender: function(view){
            
            $.ajax({
              type: 'GET',
              url: '/medics/'+ medic +'/schedules/list?office='+ calendar.data('office')+'&date1='+ view.start.format() +'&date2='+view.end.format(),
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

          }); //ajax schedules
          
          calendar.fullCalendar( 'removeEventSources')
          
            $.ajax({
              type: 'GET',
              url: '/medics/'+ medic +'/appointments/list?calendar=1&office='+ calendar.data('office') +'&date1='+ view.start.format() +'&date2='+view.end.format(),
              data: {},
              success: function (resp) {
                

                  var appointments = [];

                  $.each(resp, function( index, item ) {
                    
                      item.allDay = parseInt(item.allDay); // = false;
                      
                      if((item.patient_id != 0 && item.office_id != calendar.data('office')) || item.patient_id == 0){
                        item.rendering = 'background';
                      }
                      

                      appointments.push(item);
                  });
                 
                  //init_calendar(medic, appointments, schedules);
                  calendar.fullCalendar('addEventSource', appointments);
                  
              },
              error: function (resp) {
                  console.log('Error - '+ resp);

              }
          }); // ajax appointments

           
            
          } // view render
        
      }); //fullcalendar
    
  } //init calendar


  function crud(calendar, method, url, data, revertFunc) {
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
                
                if(resp){

                      
                      resp.allDay = parseInt(resp.allDay);

                      if(resp.allDay)
                      {
                        if(prog_schedule)
                          deleteSchedule(resp.id);
                        else
                          deleteAppointment(calendar,resp.id);
                      
                      }else{
                          
                          calendar.fullCalendar('renderEvent', resp, true);

                        }
                }else{
                  infoBox.addClass('alert-danger').html('No se pudo crear la consulta!!').show();
                        setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').hide();
                        },3000);

                   return
                }

              }
               if(method == "DELETE")
               {
                 if(resp)
                 {
                  infoBox.addClass('alert-danger').html('No se puede eliminar consulta ya que se encuentra iniciada!!').show();
                        setTimeout(function()
                        { 
                          infoBox.removeClass('alert-danger').hide();
                        },3000);

                   return resp;
                  }

                  calendar.fullCalendar('removeEvents',data.idRemove);
              
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

    function saveAppointment(calendar, event, idRemove)
    {
      slotDuration = calendar.attr('data-slotDuration');
      eventDurationNumber = moment.duration(slotDuration).asMinutes();
      eventDurationMinHours = 'minutes'; 
      
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
     
      if(isOverlapping(calendar,appointment)){
        appointment.allDay = 1;
      }

      crud(calendar ,'POST', '/clinic/appointments', appointment)

    }//save appointment

      function updateAppointment(calendar,event, revertFunc)
    {
      slotDuration = calendar.attr('data-slotDuration');
      eventDurationNumber = moment.duration(slotDuration).asMinutes();
      eventDurationMinHours = 'minutes'; 
      
      var appointment = {
        
        date : event.start.format("YYYY-MM-DD"),
        start : event.start.stripZone().format(),
        end : (event.end) ? event.end.stripZone().format() : event.start.add(eventDurationNumber, eventDurationMinHours).stripZone().format(),
        patient_id: event.patient_id,
        id: event.id,
        office_info: event.office_info,
        allDay: event.allDay
      };
      
      crud(calendar,'PUT', '/clinic/appointments/'+appointment.id, appointment, revertFunc)

    }// update appointment

    function deleteAppointment(calendar, id)
    {

      crud(calendar,'DELETE', '/clinic/appointments/'+ id + '/delete', {idRemove:id})
     
    } //delete appointment

    modalForm.on('shown.bs.modal', function (event) {
     
      var date = $(this).find('.modal-body').attr('data-date') 
      var hour = $(this).find('.modal-body').attr('data-hour')
     
  
      var modal = $(this)
      modal.find('.modal-title').html('Crear cita para el  <span class="label bg-yellow">' + date + '</span> a las <span class="label bg-yellow">'+ hour + '</span>' )
   
    });

     function createEventFromModal()
    {
     
      var currColor = "#3c8dbc"; //Red by default
      var val = modalForm.find("#modal-new-event").val();
      var valSelect = modalForm.find(".modal-body").find('.widget-user-2').attr('data-patient');//val();
      var valName = modalForm.find(".modal-body").find('.widget-user-2').attr('data-title');
      var office_id = modalForm.find(".modal-body").find('.widget-user-2').attr('data-office');
      var medic_id = modalForm.find(".modal-body").attr('data-medic');
      var date = $.fullCalendar.moment(modalForm.find('#modal-new-event').attr('data-modaldate'));
      var end = (modalForm.find('#modal-new-event').attr('data-modaldate-end')) ? $.fullCalendar.moment(modalForm.find('#modal-new-event').attr('data-modaldate-end')) : '';
      var calendar = $('#calendar-m'+medic_id);
     
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
        
        var _id = calendar.fullCalendar('renderEvent', copiedEventObject, true)[0]._id; // get _id from event in the calendar (this is for if user will remove the event)
       
       
        saveAppointment(calendar, copiedEventObject, _id);
      }
      //Remove event from text input
      modalForm.find("#modal-new-event").val("");
      //$(".modal-search-patients").val("").trigger('change');
      //$(".modal-search-patients").text("").trigger('change');
      modalForm.find('#modal-new-event').attr('data-modaldate', '');
      modalForm.modal('hide');
    } //create from modal function

     searchPatients.select2({
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
               searchPatients.empty();
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

     $(".btn-finalizar-cita").click(function (e) {
      e.preventDefault();

      createEventFromModal();
      
    });
     

    $(".dropdown-toggle").dropdown();
});