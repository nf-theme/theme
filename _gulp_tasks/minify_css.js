var gulp = require('gulp');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');
var concat = require("gulp-concat");
var bust = require('gulp-buster');
module.exports = [
    ['concat'],
    function() {
        gulp.src([
                './dist/css/app.all.css'
            ])
            .pipe(cssmin())
            .pipe(rename({
                suffix: '.min'
            }))
            .pipe(concat("app.all.min.css"))
            .pipe(gulp.dest('./dist/css/'))
            .pipe(bust({relativePath: './dist/css'}))
            .pipe(gulp.dest('.'));
    }
];
