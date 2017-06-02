var gulp = require('gulp');
var jshint = require('gulp-jshint');
module.exports = function() {
    return gulp.src('./assets/js/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
}
