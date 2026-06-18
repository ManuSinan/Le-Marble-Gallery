const mix = require('laravel-mix');

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

mix

.js('resources/views/mobile/assets/js/script.js', 'public/assets/mobile/js/')
.sass('resources/views/mobile/assets/scss/style.scss', 'public/assets/mobile/css/')

// .copyDirectory('public/assets/mobile/js/', 'resources/views/build/cordova-android/www/js/')
// .copyDirectory('public/assets/mobile/css/', 'resources/views/build/cordova-android/www/css/')
 
// .copyDirectory('public/assets/mobile/js/', 'resources/views/build/cordova-ios/www/js/')
// .copyDirectory('public/assets/mobile/css/', 'resources/views/build/cordova-ios/www/css/')
 

.js('resources/views/backend/assets/js/script.js', 'public/assets/backend/js/')
.sass('resources/views/backend/assets/scss/style.scss', 'public/assets/backend/css/')

.js('resources/views/frontend/assets/js/script.js', 'public/assets/frontend/js/')
.sass('resources/views/frontend/assets/scss/style.scss', 'public/assets/frontend/css/')

.sass('resources/sass/knm.scss', 'public/assets/knm/css/knm.css')
.sass('resources/sass/admin.scss', 'public/assets/knm/css/admin.css')

.sourceMaps(true, 'source-map')
.options({
    processCssUrls: false
})
.webpackConfig({
    stats: {
        warnings: false,
    },
})
.version()