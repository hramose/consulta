const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('app.scss')
       .webpack('app.js');

    mix.scripts([
            'app-theme.js'
        ],'public/js/app-theme.min.js','resources/assets/js');

    mix.scripts([
            'appointments.js'
        ],'public/js/appointments.min.js','resources/assets/js');

    mix.scripts([
            'schedule.js'
        ],'public/js/schedule.min.js','resources/assets/js');

    mix.scripts([
            'provinces.js'
        ],'public/js/provinces.min.js','resources/assets/js');

    mix.scripts([
            'search.js'
        ],'public/js/search.min.js','resources/assets/js');

     mix.scripts([
            'patients.js'
        ],'public/js/patients.min.js','resources/assets/js');

     mix.scripts([
            'patient_register.js'
        ],'public/js/patient_register.min.js','resources/assets/js');

    mix.scripts([
            'admin.js'
        ],'public/js/admin.min.js','resources/assets/js');

     mix.scripts([
            'clinic.appointments.js'
        ],'public/js/clinic.appointments.min.js','resources/assets/js');

      mix.scripts([
            'clinic.dailyagenda.js'
        ],'public/js/clinic.dailyagenda.min.js','resources/assets/js');

     mix.scripts([
            'clinic.patients.js'
        ],'public/js/clinic.patients.min.js','resources/assets/js');

        mix.scripts([
            'clinic.invoices.js'
        ],'public/js/clinic.invoices.min.js','resources/assets/js');

      mix.scripts([
            'assistant.invoices.js'
        ],'public/js/assistant.invoices.min.js','resources/assets/js');

       mix.scripts([
            'invoices.js'
        ],'public/js/invoices.min.js','resources/assets/js');

    mix.scripts([
        'modalRespHacienda.js'
    ], 'public/js/modalRespHacienda.min.js', 'resources/assets/js');
     
    mix.version([
        'public/js/app.js',
        'public/js/appointments.min.js',
        'public/js/schedule.min.js',
        'public/js/search.min.js',
        'public/js/patients.min.js',
        'public/js/patient_register.min.js',
        'public/js/app-theme.min.js',
        'public/js/admin.min.js',
        'public/js/clinic.appointments.min.js',
        'public/js/clinic.dailyagenda.min.js',
        'public/js/clinic.patients.min.js',
        'public/js/clinic.invoices.min.js',
        'public/js/assistant.invoices.min.js',
        'public/js/invoices.min.js',
        'public/js/modalRespHacienda.min.js',
        'public/css/app.css',
        
    
    ]);
});
