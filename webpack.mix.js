let mix = require('laravel-mix');

mix
	.sass('build/css/screen.scss', 'public/assets/css/style.css')
	.js('build/js/app.js', 'public/assets/js/app.js');