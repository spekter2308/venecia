'use script';

const gulp = require('gulp');
const sass = require('gulp-sass');

sass.compiler = require('node-sass');

const cssFiles = [
    './resources/assets/sass/custom_style.scss',
    './resources/assets/sass/custom_admin_style.scss'
];

function styles() {
    return gulp.src(cssFiles)
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./public/css'))
}

function watch() {
    gulp.watch('./resources/assets/sass/**/*.scss', styles);
}

gulp.task('styles', styles);
gulp.task('watch', watch);