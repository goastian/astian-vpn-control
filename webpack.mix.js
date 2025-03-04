const mix = require("laravel-mix");

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

/*
mix.webpackConfig({
    stats: {
        children: true,
    },
});*/
mix.js("resources/js/app.js", "js")
    .vue()
    .sass("resources/scss/app.scss", "css/app.css")
    .postCss("resources/css/app.css", "css/tailwind.css", [
        require("@tailwindcss/postcss"),
    ])
    .version();
