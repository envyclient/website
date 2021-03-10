const mix = require('laravel-mix');
require('laravel-mix-purgecss');

mix.js('resources/js/app.js', 'public/js')
    .css('resources/css/app.css', 'public/css')
    .purgeCss()
    .disableNotifications();
