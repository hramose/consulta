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

    var slotDuration = $("#setupSchedule").find('#selectSlotDurationModal').val();
   
    
    if(slotDuration)
    {
          var stepping = (slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
          
          if(stepping == '01')
          {
            stepping = '60';
          }   

         $('#datetimepicker1').datetimepicker({
            format:'YYYY-MM-DD',
            locale: 'es',
            
         });
              $('#datetimepicker2').datetimepicker({
                          format: 'HH:mm',
                          stepping: stepping,
                          //useCurrent: false
                          
                      });
             $('#datetimepicker3').datetimepicker({
                  format: 'HH:mm',
                  stepping: stepping,
                  useCurrent: false//Important! See issue #1075
              });
              
              /*$("#datetimepicker2").on("dp.change", function (e) {
                  $('#datetimepicker3').data("DateTimePicker").minDate(e.date);
              });
              $("#datetimepicker3").on("dp.change", function (e) {
                  $('#datetimepicker2').data("DateTimePicker").maxDate(e.date);
              });*/
        /* $('#datetimepicker2').datetimepicker({
                          format: 'LT',
                          stepping: stepping
                      });
        $('#datetimepicker3').datetimepicker({
                          format: 'LT',
                          stepping: stepping
                      });*/
        /*$('#datepicker').datepicker({
            autoclose: true
          });*/
         /*$(".timepicker").timepicker({
            showInputs: false
          });*/

        $('#setupSchedule').modal({backdrop:'static', show:true });
  }


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
          //user_id: $(this).data('doctor'),
          patient_id: $(this).data('patient'),
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
    function fetch_offices() {

        $.ajax({
            type: 'GET',
            url: '/medic/account/offices/list',
            data: {},
            success: function (resp) {
                console.log(resp);

                var offices = [];
                var currColor = "#00a65a";
                $.each(resp, function( index, item ) {
                   
                    

                    offices.push(item);
                    
                    var event = $("<div />");
                    event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
                    event.attr('data-patient', 0);
                    //event.attr('data-doctor', $('input[name=user_id]').val());
                    event.html('');
                    event.html(item.name);

                    $('#external-events').prepend(event);

                    //Add draggable funtionality
                    var eventObject = {
                      title: $.trim(item.name), // use the element's text as the event title
                      //user_id: $(this).data('doctor'),
                      patient_id: 0,
                      office_info: JSON.stringify(item)
                      //created_by: $(this).data('createdby')
                      
                    };

                    // store the Event Object in the DOM element so we can get to it later
                    event.data('eventObject', eventObject);
                   
                    // make the event draggable using jQuery UI
                    event.draggable({
                      zIndex: 1070,
                      revert: true, // will cause the event to go back to its
                      revertDuration: 0  //  original position after the drag
                    });



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

     var $calendar = $('#calendar');
     var minTime = $calendar.attr('data-minTime') ? $calendar.attr('data-minTime') : '06:00:00';
     var maxTime = $calendar.attr('data-maxTime') ? $calendar.attr('data-maxTime') : '18:00:00';
     var slotDuration = $('#selectSlotDuration').val() ? $('#selectSlotDuration').val() : $calendar.attr('data-slotDuration');
     var eventDurationNumber = (slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
     var eventDurationMinHours = (slotDuration.split(':')[1] == "00" ? 'hours' : 'minutes');

     $('#selectSlotDuration').on('change',function (e) {
        e.preventDefault();

       onChangeSlotDurarion($(this).val());

     });
     $('#selectSlotDurationModal').on('change',function (e) {
        e.preventDefault();
  
        onChangeSlotDurarion($(this).val());
        resetTimePicker($(this).val());
     });

     function resetTimePicker(slotDuration) {
       $('#datetimepicker2').data("DateTimePicker").clear();
       $('#datetimepicker3').data("DateTimePicker").clear();
       $('#datetimepicker2').data("DateTimePicker").destroy();
        $('#datetimepicker3').data("DateTimePicker").destroy();

  
        var stepping = (slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
       
       if(stepping == '01')
       {
          stepping = '60';
       }

       $('#datetimepicker2').datetimepicker({
                    format: 'LT',
                    stepping: stepping,
                    //useCurrent: false
                });
       $('#datetimepicker3').datetimepicker({
            format: 'LT',
            stepping: stepping,
             useCurrent: false
             //useCurrent: false//Important! See issue #1075
        });
        
       /* $("#datetimepicker2").on("dp.change", function (e) {
            $('#datetimepicker3').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker3").on("dp.change", function (e) {
            $('#datetimepicker2').data("DateTimePicker").maxDate(e.date);
        });*/
       /*$('#datetimepicker2').datetimepicker({
                    format: 'LT',
                    stepping: stepping
                });
        $('#datetimepicker3').datetimepicker({
                    format: 'LT',
                    stepping: stepping
                });*/
      
     }

     function onChangeSlotDurarion(slotDuration) {
       

        eventDurationNumber = (slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
        eventDurationMinHours = (slotDuration.split(':')[1] == "00" ? 'hours' : 'minutes');

        $calendar.fullCalendar('option','slotDuration', slotDuration);

         $.ajax({
            type: 'PUT',
            url: '/medic/account/settings',
            data: { slotDuration: slotDuration },
            success: function (resp) {
              
             console.log('slotDuration actualizado')
            },
            error: function () {
              console.log('error updating slotDuration');

            }
        });
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
              dow: [ 1, 2, 3, 4, 5, 6], // Monday - Thursday

              start: minTime, // a start time (10am in this example)
              end: maxTime, // an end time (6pm in this example)
          },
          minTime: minTime,
          maxTime: maxTime,
          selectable: true,
          selectOverlap: false,
          selectHelper: true,
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

           
           /* if($(this).data('patient'))
              $(this).remove(); // remover de citas sin agendar*/
         
           
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
                element.append('<h3>'+ event.title + '</h3>');
                
            }


             element.append('<div data-createdby="'+ event.created_by +'"></div>');

            if(event.patient_id && event.patient)
            {

              element.find(".appointment-details").click(function() {

                  swal({
                    title: 'Cita con el Paciente '+ event.patient.first_name + ' '+ event.patient.last_name,
                    text: 'Fecha: '+ event.start.format("YYYY-MM-DD") +' De: ' + event.start.format("HH:mm") + ' a: ' + event.end.format("HH:mm"),
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Ok',
                    confirmButtonText: 'Eliminar cita'
                  }).then(function () {
                    
                    deleteAppointment(event._id, event);

                    swal(
                      'Cita cancelada!',
                      'Tu cita ha sido eliminada del calendario.',
                      'success'
                    )

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

                if(event.office_info)
                {
                     var officeInfo = JSON.parse(event.office_info);

                      officeInfoDisplay = '<br>Dirección: ' + officeInfo.address + ', ' + officeInfo.province +' <br>'
                      
                      titleAlert = 'Este horario está reservado para atención en la '+ officeInfo.type +' '+ event.title
                      
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
                      
                      deleteAppointment(event._id, event);

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
        //user_id: event.user_id,
        patient_id: (event.patient_id) ? event.patient_id : 0,
        //created_by: event.created_by,
        idRemove: idRemove,
        office_info: (event.office_info) ? event.office_info : '',
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
      $('#modal-add-new-event').css({"background-color": currColor, "border-color": currColor});
    });

    function createEvent()
    {
      var val = $("#new-event").val();
      var valSelect = $(".box-create-appointment").find('.widget-user-2').attr('data-patient');
      var valName = $(".box-create-appointment").find('.widget-user-2').attr('data-title');
      if (valSelect.length == 0) {
        return;
      }
     

      //Create events
      var event = $("<div />");
      event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
      event.attr('data-patient', valSelect);
      /*event.attr('data-doctor', $('input[name=user_id]').val());
      event.attr('data-createdby', $('input[name=user_id]').val());*/
      event.html('');
      event.html(val + ' - '+ valName);
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
               $(".search-patients").empty();
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

    function createEventFromModal()
    {
      var val = $("#modal-new-event").val();
      var valSelect = $(".modal-body").find('.widget-user-2').attr('data-patient');//val();
      var valName = $(".modal-body").find('.widget-user-2').attr('data-title');
      var date = $.fullCalendar.moment($('#modal-new-event').attr('data-modaldate'));
      var end = $.fullCalendar.moment($('#modal-new-event').attr('data-modaldate-end'));
      if (valSelect.length == 0) {
        return;
      }
     

      //Create events

      var eventObject = {
          title: $.trim(val + ' - '+ valName), // use the element's text as the event title
          //user_id: $('input[name=user_id]').val(),
          patient_id: valSelect
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


    //search offices

    $(".search-offices").select2({
            placeholder: "Buscar Consultorios o selecciona no disponible",
            //tags: true,
            /* minimumInputLength: 1,*/
             /*createTag: function (params) {
              return {
                id: params.term,
                text: params.term,
                newOption: true
              }
            },*/
            templateResult: function (data) {
              var $result = $("<span></span>");

              $result.text(data.text);

              if (data.newOption) {
                $result.css("color",'red');
              }

              return $result;
            },
            ajax: {
              url: "/medic/account/offices/list",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  q: params.term // search term
                  
                };
              },
              processResults: function (data) {
               
                
               $(".search-offices").empty();
                var items = []
                
                $.each(data, function (index, value) {

                    item = {
                      id: value.name,
                      text: value.name,
                      office_info: JSON.stringify(value)
                     
                    }
                    items.push(item);
                })

                var nodisponible = {
                      id: 'No disponible',
                      text: 'No disponible',
                      office_info: '',
                      newOption: true
                    };

                items.push(nodisponible);
                    
                return {
                  results: items,
                  
                };
              }
             
            },
            templateSelection: function(container) {
                  $(container.element).attr("data-office", container.office_info);
                  
                  return container.text;
              }
     });

    $("#setupSchedule").find('.add-cita').on('click', function (e) {
      e.preventDefault();
       
        var title = $("#setupSchedule").find('#search-offices').val();
        var date = $("#setupSchedule").find('input[name="date"]').val();
        var ini = $("#setupSchedule").find('input[name="start"]').val();
        var fin = $("#setupSchedule").find('input[name="end"]').val();
       
        var dataSelect = (title) ? $("#setupSchedule").find('#search-offices').select2("data") : '';
        
        var office_info = (dataSelect) ? ((dataSelect[0].office_info) ? dataSelect[0].office_info : '') : '';
        var start = date + 'T'+ ini;
        var end = date + 'T'+ fin;
      
       if(!title)
       {
       
         $("#setupSchedule").find('#search-offices').select2('focus');
         $("#setupSchedule").find('#search-offices').select2('open');

         $('#infoBox').addClass('alert-danger').html('Escribe un consultorio o evento. Por favor revisar!!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

          return false;
       }

        if(!date || !ini || !fin)
        {
          $('#infoBox').addClass('alert-danger').html('Fecha invalida. Por favor revisar!!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

             return false;
        }
       
        if(moment(start).isAfter(end))
        {
           $('#infoBox').addClass('alert-danger').html('Fecha invalida. La hora de inicio no puede ser mayor que la hora final!!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

             return false;
        }

       var appointment = {
        title : title,
        date :  date,
        start : start,
        end : end,
        backgroundColor: (office_info) ? '#00a65a': '#dd4b39', //Success (green)
        borderColor: (office_info) ? '#00a65a': '#dd4b39',
        patient_id: 0,
        office_info: office_info,
        allDay: 0
        
      };
     
      if(isOverlapping(appointment)){
         $('#infoBox').addClass('alert-danger').html('No se puede agregar el evento por que hay colision de horarios. Por favor revisar!!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);
        return false;
      }

      $.ajax({
            type: 'POST',
            url: '/medic/appointments',
            data: appointment,
            success: function (resp) {

                  resp.allDay = parseInt(resp.allDay);
                  
                  $('#calendar').fullCalendar('renderEvent', resp, true);
              
                  $('#infoBox').addClass('alert-success').html('Evento Agregado Correctamente!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-success').hide();
                        },3000);

                  $("#setupSchedule").find('#search-offices').val([]);
                  $("#setupSchedule").find('#search-offices').text('');
                  $('#datetimepicker1').data("DateTimePicker").clear();
                  $('#datetimepicker2').data("DateTimePicker").clear();
                  $('#datetimepicker3').data("DateTimePicker").clear();
                  //$("#setupSchedule").find('input[name="start"]').val('');
                 // $("#setupSchedule").find('input[name="end"]').val('');

              
                
            },
            error: function () {
              console.log('error saving appointment');

            }
        });

     

      


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