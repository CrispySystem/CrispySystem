var gulp = require('gulp');
var cssimport = require('gulp-cssimport');
var concat = require('gulp-concat');
var cleancss = require('gulp-clean-css');

cssSources = [
    './resources/vendor/adminbsb-materialdesign/plugins/bootstrap/css/bootstrap.css',
    './resources/vendor/adminbsb-materialdesign/plugins/node-waves/waves.css', // Material Design Click wave
    './resources/vendor/adminbsb-materialdesign/plugins/animate-css/animate.css',
    './resources/vendor/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css',
    './resources/vendor/adminbsb-materialdesign/plugins/font-awesome/css/font-awesome.css',
    './resources/vendor/adminbsb-materialdesign/css/style.css',
    './resources/vendor/adminbsb-materialdesign/css/themes/all-themes.css'
];

gulp.task('vendor.css', function() {
    return gulp.src(cssSources)
        .pipe(cssimport())
        .pipe(concat('vendor.min.css'))
        .pipe(cleancss())
        .pipe(gulp.dest('./public/css/'));
});

jsSources = [
    './resources/vendor/adminbsb-materialdesign/plugins/jquery/jquery.js',
    './resources/vendor/adminbsb-materialdesign/plugins/bootstrap/js/bootstrap.js',
    './resources/vendor/adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js',
    './resources/vendor/adminbsb-materialdesign/plugins/jquery-slimscroll/jquery.slimscroll.js',
    './resources/vendor/adminbsb-materialdesign/plugins/node-waves/waves.js',
    './resources/vendor/adminbsb-materialdesign/plugins/jquery-datatable/jquery.dataTables.js',
    './resources/vendor/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js',
    './resources/vendor/adminbsb-materialdesign/js/admin.js'
];

gulp.task('vendor.js', function() {
    return gulp.src(jsSources)
        .pipe(concat('vendor.min.js'))
        .pipe(gulp.dest('./public/js'));
});

fontSources = [
    './resources/vendor/adminbsb-materialdesign/plugins/bootstrap/fonts/*',
    './resources/vendor/adminbsb-materialdesign/plugins/font-awesome/fonts/*'
];

gulp.task('vendor.fonts', function() {
    return gulp.src(fontSources)
        .pipe(gulp.dest('./public/fonts'));
});

gulp.task('vendor', ['vendor.css', 'vendor.js', 'vendor.fonts']);