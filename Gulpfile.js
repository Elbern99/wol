'use strict';
 
var gulp = require('gulp');
var sass = require('gulp-ruby-sass');
var watch = require('gulp-watch');
var cleanCSS = require('gulp-clean-css');
var autoPrefixer = require('gulp-autoprefixer');
var csslint = require('gulp-csslint');

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
});