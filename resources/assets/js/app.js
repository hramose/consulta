
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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


});

$(window).scroll(function () {
          if ($(this).scrollTop() > 50) {
              $('.menu-fixed').addClass("scroll");
              
          } else {
              $('.menu-fixed').removeClass("scroll");
              
          }
          
      });

$('.form-update-location').on('submit', function (e) {
   e.preventDefault();
   var form = $(this);

   window.navigator.geolocation.getCurrentPosition(function (geo) {
      

    var office_id = form.find('input[name="id"]').val();
    var lat = geo.coords.latitude;
    var lon = geo.coords.longitude;
    
      if(office_id)
      {
        $.ajax({
              type: 'PUT',
              url: '/medic/account/offices/'+ office_id + '/notification',
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






