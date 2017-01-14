
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
Vue.component('patient', require('./components/Patient.vue'));
Vue.component('patients', require('./components/Patients.vue'));
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


