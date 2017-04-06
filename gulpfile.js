var elixir = require('laravel-elixir');
var paths = {
        'bootstrap': './node_modules/bootstrap-sass/assets/',
        'jquery' : './bower_components/jquery/'
    }

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

elixir(function(mix) {
    mix.sass("app.scss", 'public/css/', {includePaths: [paths.bootstrap + 'stylesheets/']})
    .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts')
    .scripts([
        paths.jquery + "dist/jquery.js",
        "./js/jquery.min.js",
        "./js/angular.min.js",
        "./js/angular-route.min.js",
        "./js/bootstrap-tagsinput.min.js",
        "./js/bootstrap.min.js",
        "./js/plugins/morris/raphael.min.js",
        "./js/plugins/morris/morris.min.js",
        "./js/metisMenu.min.js",
        "./js/sb-admin-2.js",
        "./datatables/js/jquery.dataTables.min.js",
        "./datatables-plugins/dataTables.bootstrap.min.js",
        "./datatables-responsive/dataTables.responsive.js",
        "./js/behigorri.js"

        paths.bootstrap + "javascripts/bootstrap.js",
        "./js/"
    ], 'public/js/app.js', './');
});
