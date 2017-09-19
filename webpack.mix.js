let mix = require('laravel-mix');

mix.disableNotifications();
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
// backend
mix.js('resources/assets/backend/js/app.js', 'public/backend/js')
   .sass('resources/assets/backend/sass/app.scss', 'public/backend/css');


if (mix.config.inProduction) {
    mix.version();
}
