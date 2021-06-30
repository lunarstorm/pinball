/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
const mix = require('laravel-mix');
const path = require('path');
const webpack = require('webpack');

require('laravel-mix-clean');
require('laravel-mix-definitions');

let BASE = process.env.MIX_BASE || 'dev'

if (mix.inProduction()) {
	console.log('In Production');
	BASE = 'dist';
	mix.clean();
}

const PUBLIC_PATH = path.join('public', BASE);

mix.version();

mix.setPublicPath(PUBLIC_PATH)
		.setResourceRoot(path.join('/', BASE))
		.js('resources/js/app.js', 'js')
		.sass('resources/sass/app.scss', 'css')
		.vue()
		.sourceMaps()
		.autoload({
			jquery: ['$', 'window.jQuery', 'jQuery'],
			lodash: ['_'],
			popper: ['Popper'],
		})
		.webpackConfig({
			output: {
				publicPath: path.join('/', BASE, '/')
			},
			plugins: [
				new webpack.DefinePlugin({
					'__VUE_OPTIONS_API__': true,
					'__VUE_PROD_DEVTOOLS__': false,
				})
			]
		})
		.alias({
			'vio': path.resolve('resources/js/vio/src'),
			'@': path.resolve('resources/js'),
		})