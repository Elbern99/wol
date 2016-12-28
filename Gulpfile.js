'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-ruby-sass');
var watch = require('gulp-watch');
var cleanCSS = require('gulp-clean-css');
var autoPrefixer = require('gulp-autoprefixer');
var csslint = require('gulp-csslint');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var pump = require('pump');
var optimizejs = require('gulp-optimize-js');

gulp.task('lint', function() {
    return gulp.src('./frontend/web/js_base/**/base.js')
        .pipe(jshint())
        .pipe(jshint.extract('auto|always|never'))
        .pipe(jshint.reporter('default', { verbose: true }))
        .pipe(gulp.dest('./frontend/web/js/'));
});

gulp.task('compress', function (cb) {
    pump([
            gulp.src('./frontend/web/js_base/**/base.js'),
            uglify(),
            gulp.dest('./frontend/web/js/')
        ],
        cb
    );
});

gulp.task('optimize', function() {
    gulp.src('./frontend/web/js_base/**/base.js')
        .pipe(optimizejs())
        .pipe(gulp.dest('./frontend/web/js/'))
});

gulp.task('sass', function () {
    sass('./frontend/web/scss/**/*.scss')
        .on('error', sass.logError)
        //.pipe(cleanCSS({compatibility: 'ie10'}))
        .pipe(csslint())
        .pipe(autoPrefixer({
            browsers: ['last 2 versions']
        }))
    .pipe(gulp.dest('./frontend/web/css/'));
});

gulp.task('sass:watch', function () {
    gulp.watch('./frontend/web/scss/**/*.scss', ['sass']);
    //gulp.watch('./frontend/web/js_base/**/base.js', ['lint']);
});