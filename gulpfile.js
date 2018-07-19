require('dotenv').config();
var gulp = require('gulp');
var browser = require('browser-sync').create();
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');
var sourcemaps = require('gulp-sourcemaps');
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var rename = require('gulp-rename');

var CONFIG = {
    CSS_COMPATIBILITY: [
        'last 2 versions',
        'ie >= 9',
        'Android >= 2.3',
        'ios >= 7'
    ]
};

// Compiles Sass files into CSS
gulp.task('sass', function () {
    return gulp.src(['src/scss/assets/foundation-float.scss'])
        .pipe(sourcemaps.init())
        .pipe(plumber())
        .pipe(sass({
            outputStyle: "compressed"
        }).on('error', sass.logError))
        .pipe(postcss([autoprefixer({
            browsers: CONFIG.CSS_COMPATIBILITY
        })]))
        .pipe(rename('site.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('public/css/'))
});

gulp.task('watch', ['sass'], function () {
    browser.init({
        proxy: process.env.PROXY_ADDRESS,
    });
    gulp.watch('src/scss/*.scss', ['sass', browser.reload]);
    gulp.watch(["public/js/*", "templates/*", "templates/*/*", "*.php"]).on('change', browser.reload);
});

gulp.task('bs', function() {
    browser.init({
        proxy: process.env.PROXY_ADDRESS,
    });
    gulp.watch(["public/js/*", "templates/*", "templates/*/*", "*.php"]).on('change', browser.reload);
});

gulp.task('default', ['watch']);