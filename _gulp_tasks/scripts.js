var gulp = require('gulp');
var concat = require('gulp-concat');
module.exports = function() {
    return gulp.src([
            './bower_components/bootstrap/dist/js/bootstrap.js',
            './assets/js/scripts/*.js',
        ])
        .pipe(concat('scripts.js'))
        .pipe(gulp.dest('./dist/js/'));
}
