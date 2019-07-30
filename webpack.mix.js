const mix = require('laravel-mix');
const argv = require('yargs').argv;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

if (typeof(argv.env) === 'undefined' || argv.env.build == 'admin') {
    // TODO: add scripts need for bundle
    // mix.scripts([
        
    // ], 'public/js/admin_lib.js');
    mix.js('resources/js/admin.js', 'public/js/admin.js').version();

    // TODO: add stylesheets need for bundle
    // mix.styles([

    // ], 'public/css/admin.css');

    // TODO: copy resources (images, fonts,...)
    // mix.copyDirectory('resources/images/web', 'public/images/web');
}
