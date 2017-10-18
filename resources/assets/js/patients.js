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
       $('.breadcrumb').hide();
       
    }else{
      //$('.box-create-appointment').show();
      $('.box-search-filters').removeClass('collapsed-box');
    }

    $("form[data-confirm]").submit(function() {
      if ( ! confirm($(this).attr("data-confirm"))) {
          return false;
      }
  });


   $(".dropdown-toggle").dropdown();

    var slotDuration = $('#initAppointment').find('.modal-body').attr('data-slotDuration');
    var eventDurationNumber = moment.duration(slotDuration).asMinutes(); //(slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
    var eventDurationMinHours = 'minutes'; //(slotDuration.split(':')[1] == "00" ? 'hours' : 'minutes');
    var stepping = moment.duration(slotDuration).asMinutes();//(slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
          
          // if(stepping == '01')
          // {
          //   stepping = '60';
          // }   
    
    $('#datetimepicker1').datetimepicker({
            format:'YYYY-MM-DD',
            locale: 'es',
            defaultDate: new Date()

            
         });
              $('#datetimepicker2').datetimepicker({
                          format: 'HH:mm',
                          stepping: stepping,
                          defaultDate: new Date()
                          //useCurrent: false
                          
                      });
             $('#datetimepicker3').datetimepicker({
                  format: 'HH:mm',
                  stepping: stepping,
                  useCurrent: false//Important! See issue #1075
              });

     $('#initAppointment').on('shown.bs.modal', function (event) {
          //debugger
          var button = $(event.relatedTarget)
          var patient_id = button.attr('data-patient');
          var patient = button.attr('data-patientname');
        
         
          $(this).find('.modal-body').find('#patient-name').text(patient);
          $(this).find('.modal-body').attr('data-modalpatient',patient_id);
          
        
     
       
      });

      /** load events from db **/
      var appointmentsFromCalendar = [];
      fetch_events();
      function fetch_events() {

        $.ajax({
            type: 'GET',
            url: '/medic/appointments/list?calendar=1&date1='+moment().format('YYYY-MM-DD')+'&date2='+moment().add(3,'months').format('YYYY-MM-DD'),
            data: {},
            success: function (resp) {
                console.log(resp);

                appointmentsFromCalendar = [];

                $.each(resp, function( index, item ) {
                   
                    item.allDay = parseInt(item.allDay); // = false;
                    

                    appointmentsFromCalendar.push(item);
                });
               
               
                
            },
            error: function () {
                console.log('Error - '+ resp);

            }
        });


    }

      function isOverlapping(event) {
     
        var array = appointmentsFromCalendar;
        
          for(i in array){
              
                if (event.end > array[i].start && event.start < array[i].end){
                   return true;
                }
             
          }
          return false;
    }

    $(".search-offices").select2({
            placeholder: "Selecciona el Consultorio donde quiere iniciar la consulta",
            //tags: true,
            /* minimumInputLength: 1,*/
             /*createTag: function (params) {
              return {
                id: params.term,
                text: params.term,
                newOption: true
              }
            },*/
            /*templateResult: function (data) {
              var $result = $("<span></span>");

              $result.text(data.text);

              if (data.newOption) {
                $result.css("color",'red');
              }

              return $result;
            },*/
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
                      id: value.id,
                      text: value.name,
                      office_info: JSON.stringify(value)
                     
                    }
                    items.push(item);
                })

                /*var nodisponible = {
                      id: 'No disponible',
                      text: 'No disponible',
                      office_info: '',
                      newOption: true
                    };

                items.push(nodisponible);*/
                    
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


     $("#initAppointment").find('.add-cita').on('click', function (e) {
        e.preventDefault();
        
        var modal = $("#initAppointment");
        var patient_id = modal.find('.modal-body').attr('data-modalpatient');
        var office_id = modal.find('#search-offices').val();
        var date = modal.find('input[name="date"]').val();
        var ini = modal.find('input[name="start"]').val();
        var fin = modal.find('input[name="end"]').val();
       
        var start = date + 'T'+ ini + ':00';
        var end = date + 'T'+ ((fin) ? fin : ini) + ':00';
      
       if(!office_id)
       {
       
         $("#initAppointment").find('#search-offices').select2('focus');
         $("#initAppointment").find('#search-offices').select2('open');

         $('#infoBox').addClass('alert-danger').html('Escribe un consultorio. Por favor revisar!!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

          return false;
       }

        if(!date || !ini )
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

      

         if(moment(start).isSame(end))
        {
          end = moment(start).add(eventDurationNumber, eventDurationMinHours).stripZone().format();
        }

       var appointment = {
        title : 'Cita',
        date :  date,
        start : start,
        end : end,
        backgroundColor:  '#3c8dbc', //Success (green)
        borderColor: '#3c8dbc',
        patient_id: patient_id,
        office_id: office_id,
        office_info: '',
        allDay: 0
        
      };
     
      if(isOverlapping(appointment)){
         $('#infoBox').addClass('alert-danger').html('No se puede iniciar esta cita por que ya hay una cita en el mismo horario. Por favor revisa tus citas programadas !!!').show();
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
                  
                  $("#initAppointment").find('.add-cita').attr('disabled','disabled');
                  $("#initAppointment").find('.btn-cancel').attr('disabled','disabled');
                  $('#infoBox').addClass('alert-success').html('Cita Creada Correctamente!!. Iniciando su cita, espere un momento').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-success').hide();
                            modal.find('#patient-name').text('');
                            modal.find('.modal-body').attr('data-modalpatient', '');
                            modal.find('#datetimepicker1').data("DateTimePicker").clear();
                            modal.find('#datetimepicker2').data("DateTimePicker").clear();
                            modal.find('#datetimepicker3').data("DateTimePicker").clear();

                            modal.modal('hide');
                            $("#initAppointment").find('.add-cita').removeAttr('disabled');
                            $("#initAppointment").find('.btn-cancel').removeAttr('disabled');
                           window.location.href = "/medic/appointments/" + resp.id + "/edit";
                        },3000);

                  
                  

              
                
            },
            error: function () {
              console.log('error saving appointment');

            }
        });

     

      


    });



    initCalendar([]);
    
        function initCalendar(appointments)
        {
           var calendar = $('#calendar');
           
          calendar.fullCalendar({
              locale: 'es',
              defaultView: 'month',
              timeFormat: 'h(:mm)a',
              header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
              },
              events: appointments,
              nowIndicator: true,
              timezone: 'local',
              allDaySlot: false,
             
            
          
            dayClick: function(date, jsEvent, view) {
              
                  
                  if (view.name === "month") {
                      
                      //calendar.fullCalendar('gotoDate', date);
                      //calendar.fullCalendar('changeView', 'agendaWeek');
                      dateFrom = date;
                      dateTo = dateFrom;
                      $.ajax({
                        type: 'GET',
                        url: '/medic/schedules/list?date1='+dateFrom.format()+'&date2='+ dateTo.format(),
                        data: {},
                        success: function (resp) {
                           
  
                            var schedulesForAppointmentPage = [];
                           
                            $.each(resp, function( index, item ) {
                               
                                item.allDay = parseInt(item.allDay); // = false;
                                
                                item.schedule = 1;
                                schedulesForAppointmentPage.push(item);
                               
  
                            });
  
                           
                    
                    
                           
                           
                          console.log(schedulesForAppointmentPage)
  
                        },
                        error: function (resp) {
                            console.log('Error - '+ resp);
  
                        }
                    }); //ajax schedules
    
                      return false;
                  }
    
                 
                  
               
              }
              
    
    
    
             
            
          });
    
        } // init calendar




});