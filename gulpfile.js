var elixir = require('laravel-elixir');
require('laravel-elixir-vueify-2.0');

elixir(function(mix) {
 mix.phpUnit()

    /**
     * Copy needed files from /node directories
     * to /public directory.
     */
     .copy(
       'node_modules/font-awesome/fonts',
       'public/build/fonts/font-awesome'
     )
     .copy(
       'node_modules/bootstrap-sass/assets/fonts/bootstrap',
       'public/build/fonts/bootstrap'
     )
     .copy(
       'node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
       'public/js/vendor/bootstrap'
     )

     /**
      * Process frontend SCSS stylesheets
      */
     .sass([
        'frontend/app.scss',
        'plugin/sweetalert/sweetalert.scss',
        'font.scss'
     ], 'resources/assets/css/frontend/app.css')

     /**
      * Combine pre-processed frontend CSS files
      */
     .styles([
        'frontend/app.css'
     ], 'public/css/frontend.css')

     /**
      * Combine frontend scripts
      */
     .scripts([
        'plugin/sweetalert/sweetalert.min.js',
        'plugins.js',
        'frontend/app.js'
     ], 'public/js/frontend.js')

     /**
      * Process backend SCSS stylesheets
      */
     .sass([
         'backend/app.scss',
         'backend/plugin/toastr/toastr.scss',
         'plugin/sweetalert/sweetalert.scss',
         'font.scss',
         'main.scss'
     ], 'resources/assets/css/backend/app.css')

     /**
      * Combine pre-processed backend CSS files
      */
     .styles([
         'backend/app.css'
     ], 'public/css/backend.css')
     .styles([
        'backend/student/search.css'
     ], 'public/css/student_search.css')
     .styles([
        'plugin/handsontable/handsontable.full.min.css'
     ], 'public/css/handsontable.full.min.css')
     .styles([
         'backend/student/transcript.css'
     ], 'public/css/student_transcript.css')
     .styles([
       'backend/student/foundation_certificate.css'
     ], 'public/css/foundation_certificate.css')

     /**
      * Combine backend scripts
      */
     .scripts([
         'plugin/sweetalert/sweetalert.min.js',
         'plugins.js',
         'backend/app.js',
         'backend/plugin/toastr/toastr.min.js',
         'backend/custom.js'
     ], 'public/js/backend.js')
     .scripts([
         'plugin/handsontable/handsontable.full.min.js'
     ], 'public/js/handsontable.full.min.js')
     .browserify('backend/student/search.js','public/js/student_search.js')
    /**
      * Apply version control
      */
     .version([
       "public/css/frontend.css",
       "public/js/frontend.js",
       "public/css/backend.css",
       "public/css/student_search.css",
       "public/css/student_transcript.css" ,
       "public/css/foundation_certificate.css",
       "public/js/backend.js",
       "public/js/student_search.js",
       'public/js/handsontable.full.min.js',
       'public/css/handsontable.full.min.css'
     ]);
});