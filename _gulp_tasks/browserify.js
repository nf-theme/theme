var gulp = require('gulp');
var browserify = require('gulp-browserify');
module.exports = function() {
    return gulp.src('./assets/js/browserify/app.js')
        .pipe(browserify({
            transform: ['babelify'],
        }))
        .pipe(gulp.dest('./dist/js'));
}
