var gulp = require('gulp');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var bust = require('gulp-buster');
module.exports = [
    ['scripts'],
    function() {
        return gulp.src('./dist/js/scripts.js')
            .pipe(uglify({
                mangle: false
            }))
            .pipe(rename({
                extname: ".min.js"
            }))
            .pipe(gulp.dest('./dist/js/'))
            .pipe(bust({
                relativePath: './dist/js'
            }))
            .pipe(gulp.dest('.'));
    }
];
