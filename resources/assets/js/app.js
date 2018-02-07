
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// chartjs package
require('chart.js');
// vue-charts package
require('hchs-vue-charts');
Vue.use(VueCharts);

const VueInputMask = require('vue-inputmask').default

Vue.use(VueInputMask)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.component('alert', require('./components/Alert.vue'));
Vue.component('history', require('./components/History.vue'));
Vue.component('medicines', require('./components/Medicines.vue'));
Vue.component('diseasenotes', require('./components/Diseasenotes.vue'));
Vue.component('physicalexam', require('./components/PhysicalExam.vue'));
Vue.component('diagnostics', require('./components/Diagnostics.vue'));
Vue.component('instructions', require('./components/Instructions.vue'));
Vue.component('signs', require('./components/Signs.vue'));
Vue.component('summary-appointment', require('./components/Summary.vue'));
Vue.component('office', require('./components/Office.vue'));
Vue.component('modal-schedule', require('./components/ModalSchedule.vue'));
Vue.component('modal-appointments', require('./components/ModalAppointments.vue'));
Vue.component('modal-clinic-appointments', require('./components/ModalClinicAppointments.vue'));
Vue.component('patient-form', require('./components/PatientForm.vue'));
Vue.component('patient-list', require('./components/PatientList.vue'));
Vue.component('patient-search', require('./components/PatientSearch.vue'));
Vue.component('appointment-create', require('./components/AppointmentCreate.vue'));
Vue.component('modal-reminder', require('./components/ModalReminder.vue'));
Vue.component('clinic', require('./components/Clinic.vue'));
Vue.component('reports-clinic', require('./components/ReportsClinic.vue'));
Vue.component('treatments', require('./components/Treatments.vue'));
Vue.component('poll', require('./components/Poll.vue'));
Vue.component('reports-medics', require('./components/ReportsMedics.vue'));
Vue.component('reports-clinics', require('./components/ReportsClinics.vue'));
Vue.component('reports-patients', require('./components/ReportsPatients.vue'));
Vue.component('invoice-form', require('./components/InvoiceForm.vue'));
Vue.component('invoice-list', require('./components/InvoiceList.vue'));
Vue.component('assistant-form', require('./components/AssistantForm.vue'));
Vue.component('assistant-list', require('./components/AssistantList.vue'));
Vue.component('reports-medic', require('./components/ReportsMedic.vue'));
Vue.component('allergies', require('./components/Allergies.vue'));
Vue.component('pressure-control', require('./components/PressureControl.vue'));
Vue.component('sugar-control', require('./components/SugarControl.vue'));
Vue.component('reports-incomes', require('./components/ReportsIncomes.vue'));
Vue.component('notes', require('./components/Notes.vue'));
Vue.component('photo-upload', require('./components/PhotoUpload.vue'));
Vue.component('lab-results', require('./components/LabResults.vue'));
Vue.component('lab-exams', require('./components/LabExams.vue'));
Vue.component('notifications', require('./components/Notifications.vue'));
Vue.component('payment-details', require('./components/PaymentDetails.vue'));
Vue.component('table-subscriptions', require('./components/TableSubscriptions.vue'));
Vue.component('table-pending-payments', require('./components/TablePendingPayments.vue'));
Vue.component('clinic-notifications', require('./components/ClinicNotifications.vue'));
Vue.component('test-conexion-hacienda', require('./components/TestConexionHacienda.vue'));
//Vue.component('wizard-schedule', require('./components/WizardSchedule.vue'));
/*import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);**/


window.bus = new Vue();


const app = new Vue({
    el: '#app',
    data: {
       message: {
         show:false,
         text: "",
         type: "info"
       }
    },
    created() {
      bus.$on('alert', this.alertMessage);
      
    },
    methods:{
    	 alertMessage (message, type = "info") {
          console.log('aler from main app');
          this.message.text = message;
          this.message.show = true;
          this.message.type = type;
          setTimeout(
            () => {
              this.message.show = false,
              this.message.text = ""
            },
            3000
          )
        }
    
    }
});

$(window).on('load', function() {

    $('.preloader').addClass('animated fadeOut').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
      $('.preloader').hide();
      
    });

    $('body').on('click', '.popup-youtube', function (e) {
        e.preventDefault();

        $(this).magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            iframe: {
                patterns: {
                    youtube: {
                        index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

                        id: 'v=', // String that splits URL in a two parts, second part should be %id%
                        // Or null - full URL will be returned
                        // Or a function that should return %id%, for example:
                        // id: function(url) { return 'parsed id'; }

                        src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0&showinfo=0' // URL that will be set as a source for iframe.
                    },


                },


            },
            fixedContentPos: false
        }).magnificPopup('open');
        
    });
    // $('body').find('.popup-youtube').magnificPopup({
    //     disableOn: 700,
    //     type: 'iframe',
    //     mainClass: 'mfp-fade',
    //     removalDelay: 160,
    //     preloader: false,
    //     iframe: {
    //         patterns: {
    //             youtube: {
    //                 index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

    //                 id: 'v=', // String that splits URL in a two parts, second part should be %id%
    //                 // Or null - full URL will be returned
    //                 // Or a function that should return %id%, for example:
    //                 // id: function(url) { return 'parsed id'; }

    //                 src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0&showinfo=0' // URL that will be set as a source for iframe.
    //             },


    //         },


    //     },
    //     fixedContentPos: false
    // });

   


});

$(window).scroll(function () {
          if ($(this).scrollTop() > 50) {
              $('.menu-fixed').addClass("scroll");
              
          } else {
              $('.menu-fixed').removeClass("scroll");
              
          }
          
      });

  $('.notifications-menu .dropdown-menu').on({
    "click":function(e){
        e.stopPropagation();
      }
  });
$('.form-update-location').on('submit', function (e) {
   e.preventDefault();
   var form = $(this);
   var role = (form.data('role')) ? form.data('role'): 'medic';
   window.navigator.geolocation.getCurrentPosition(function (geo) {
      

    var office_id = form.find('input[name="id"]').val();
    var lat = geo.coords.latitude;
    var lon = geo.coords.longitude;
    
      if(office_id)
      {
        $.ajax({
              type: 'PUT',
              url: '/'+role +'/account/offices/'+ office_id + '/notification',
              data: { notification: 0, lat: lat, lon: lon },
              success: function (resp) {
                
               console.log('Notificacion actualizada')
               form.parent('.notification-app').hide();
              },
              error: function () {
                console.log('error updating Notificacion');

              }
          });
      }
   });
    
    console.log('update location');
    
    
});

var modalForm = $('#contact-modal')

modalForm.on('shown.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var user = button.attr('data-user');
        $('.fa-spin').hide();
    
        var modal = $(this);

          
            modal.find('#modal-contact-user').val(user)

});



modalForm.find('.modal-contact-btn-send').on('click', function (e) {
    e.preventDefault();
    var button = $(this);
    var form = modalForm.find('#modal-contact-form');
    var formData = form.serializeArray();
    
    formData.push({ name: '_token', value:$('meta[name="csrf-token"]').attr('content')});
    
    $('.fa-spin').show();

    button.attr('disabled','disabled')
    $.ajax({
        type: 'POST',
        url: '/support',
        data: formData,
        success: function (resp) {

            $('.fa-spin').hide();
            button.attr('disabled',false)

            if(resp == 'ok'){
                modalForm.find('#modal-contact-msg').val('');

                alert('Mensaje Enviado');
            }
        },
        error: function () {
            
            $('.fa-spin').hide();
            button.attr('disabled',false)

            console.log('error  reminder');
            

        }
    });

});

$('#selected_clinic').on('change', function (e) {
   

    $(this).parent('form').submit();

});






