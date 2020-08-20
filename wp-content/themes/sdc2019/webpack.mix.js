const mix = require('laravel-mix');
const themeFolder = 'sdc2019';
// const themePath = 'wp-content/themes/' + themeFolder + '/';
const themePath = '';

mix.js(themePath + 'js/imports.js', themePath + 'js/main.js')
.sass(themePath + 'scss/imports.scss', themePath + 'css/main.css')
.options({
  // Allows us to use relative paths (e.g. background-img: url()) in scss files.
  processCssUrls: false
})
.autoload({
    jquery: ['$', 'jQuery']
})
// .sass(themePath + 'scss/login.scss', themePath + 'css/login.css')
// Where mix-manifest.json is saved.
// .setPublicPath('wp-content/themes/' + themeFolder + '/')
.setPublicPath('/')
.setResourceRoot('/')
// Extra debug info for compiled files.
.sourceMaps();

// Setup live reload.
// Options:
// https://github.com/statianzo/webpack-livereload-plugin/blob/master/README.md
var LiveReloadPlugin = require('webpack-livereload-plugin');
mix.webpackConfig({
  plugins: [
    new LiveReloadPlugin({
        // protocol: 'http',
        // port: '35729',
        // hostname: 'localhost',
        appendScriptTag: true, //no need to manually add the script to the page.
        // ignore: null,
        // delay: 0
      }),
  ],
});
