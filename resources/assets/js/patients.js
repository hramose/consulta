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

   $(".dropdown-toggle").dropdown();

    var slotDuration = $('#initAppointment').find('.modal-body').attr('data-slotDuration');

    var stepping = (slotDuration.split(':')[1] == "00" ? slotDuration.split(':')[0] : slotDuration.split(':')[1]);
          
          if(stepping == '01')
          {
            stepping = '60';
          }   
    
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
            url: '/medic/appointments/list',
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


     $("#initAppointment").find('.add-cita').on('click', function (e) {
        e.preventDefault();
        
        var modal = $("#initAppointment");
        var patient_id = modal.find('.modal-body').attr('data-modalpatient');
        var date = modal.find('input[name="date"]').val();
        var ini = modal.find('input[name="start"]').val();
        var fin = modal.find('input[name="end"]').val();
       
        var start = date + 'T'+ ini;
        var end = date + 'T'+ fin;
      
       /*if(!patient_id)
       {
       
         $("#setupSchedule").find('#search-offices').select2('focus');
         $("#setupSchedule").find('#search-offices').select2('open');

         $('#infoBox').addClass('alert-danger').html('Escribe un consultorio o evento. Por favor revisar!!!').show();
                        setTimeout(function()
                        { 
                          $('#infoBox').removeClass('alert-danger').hide();
                        },3000);

          return false;
       }*/

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
        title : 'Cita',
        date :  date,
        start : start,
        end : end,
        backgroundColor:  '#3c8dbc', //Success (green)
        borderColor: '#3c8dbc',
        patient_id: patient_id,
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




});