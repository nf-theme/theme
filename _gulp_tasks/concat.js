var gulp = require('gulp');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');
var concat = require("gulp-concat");
module.exports = [
    ['sass'],
    function() {
        gulp.src([
                './bower_components/bootstrap/dist/css/bootstrap.css',
                './bower_components/bootstrap/dist/css/bootstrap-theme.css',
                './bower_components/slick-carousel/slick/slick.css',
                './bower_components/animate.css/animate.min.css',
                './dist/css/app.css'
            ])
            .pipe(concat("app.all.css"))
            .pipe(gulp.dest('./dist/css/'));
    }
];
