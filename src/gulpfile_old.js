'use strict';
// sass compile
var gulp = require('gulp');
var sass = require('gulp-sass');
var elixir = require('laravel-elixir');


// custom scripts
elixir(function (mix) {
    mix.sass([
        'custom_style.scss',
    ], 'public/css/custom.css');
    mix.sass([
        'custom_admin_style.scss',
    ], 'public/css/custom_admin.css');
    mix.version(['public/css/custom.css', 'public/css/custom_admin.css']);
});