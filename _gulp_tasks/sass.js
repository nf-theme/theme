var gulp = require('gulp');
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
module.exports = function() {
    return gulp.src('./assets/css/scss/app.scss')
        .pipe(sassGlob())
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./dist/css'));
}
