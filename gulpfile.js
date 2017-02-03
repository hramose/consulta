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

 
    mix.version([
        'public/js/app.js',
        'public/js/appointments.min.js',
        'public/js/schedule.min.js',
        'public/js/search.min.js',
        'public/js/app-theme.min.js',
        'public/css/app.css',
        
    
    ]);
});
