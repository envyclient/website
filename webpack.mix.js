const mix = require('laravel-mix')
require('laravel-mix-purgecss')

mix.js('resources/js/app.js', 'public/js')
    .css('resources/css/app.css', 'public/css')
    .disableNotifications()
/*    .purgeCss({
        extend: {
            content: [path.join(__dirname, 'vendor/spatie/menu/!**!/!*.php')],
            safelist: {deep: [/hljs/]},
        }
    });*/
