var gulp = require('gulp');

var requireDir = require('require-dir');
requireDir('./gulp');

gulp.task('default', function() {
    console.log('Please run a task');
});