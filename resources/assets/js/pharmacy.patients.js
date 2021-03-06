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
        format: 'YYYY-MM-DD',
        locale: 'es',
        defaultDate: new Date()


    });
             $('#datetimepicker4').datetimepicker({
                  format: 'HH:mm',
                  stepping: stepping,
                  useCurrent: false//Important! See issue #1075
              });
    
    $('#datetimepicker5').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'es',
        defaultDate: new Date()


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
   



     





});