let mix = require('laravel-mix');

mix.sass('resources/sass/index.scss', 'public/css/styles.css')
    .setPublicPath('public')
    .version();