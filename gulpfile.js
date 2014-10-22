// Include gulp and plugins
var gulp         = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    sass         = require('gulp-sass'),
    neat         = require('node-neat').includePaths,
    concat       = require('gulp-concat'),
    uglify       = require('gulp-uglify'),
    gutil        = require('gulp-util'),
    newer        = require('gulp-newer'),
    imagemin     = require('gulp-imagemin'),
    svgo         = require('gulp-svgo'),
    ngAnnotate   = require('gulp-ng-annotate'),
    rename       = require('gulp-rename');

// Error Handling
var onError = function (err) {
    gutil.beep();
    console.log(err);
};

// Compile Our Sass
gulp.task('sass', function() {
    return gulp.src('assets/css/scss/**/*.scss')
        .on('error', onError)
        .pipe(sass({
            includePaths: ['styles'].concat(neat)
        }))
        .pipe(autoprefixer('last 2 version'))
        .pipe(gulp.dest('src/public/dist/css'));
});

gulp.task('angular', function() {
    return gulp.src(['assets/js/app/**/*.js'])
        .pipe(ngAnnotate())
        // disabling for now while debugging
//        .pipe(uglify())
        .pipe(gulp.dest('src/public/dist/js/app'))
});
// Concatenate & Minify JS
gulp.task('scripts', function() {
    // Gather our files
    gulp.src([
        'assets/js/vendors/modernizr/modernizr.min.js',
        'assets/js/vendors/jquery/jquery.min.js',
        'assets/js/vendors/angularjs/angular.js'
    ])
        .pipe(concat('head.min.js'))
        .pipe(ngAnnotate())
        .pipe(gulp.dest('src/public/dist/js'));
    return gulp.src([
        'assets/js/vendors/angular-file-upload/*.js',
        'assets/js/vendors/text-angular/*.js',
        'assets/js/vendors/*.js',
        'assets/js/init.js',
        'assets/js/app/services/util.js',
        'assets/js/app/services/alert.js',
        'assets/js/app/controllers/AlertCtrl.js'
    ])
        .pipe(concat('scripts.min.js'))
        .pipe(ngAnnotate())
        // disabling for now while debugging
//        .pipe(uglify())
        .pipe(gulp.dest('src/public/dist/js'));
});

// Optimize images
gulp.task('images', function() {
    return gulp.src('assets/img/**/*')
        .pipe(newer('img'))
        .pipe(imagemin())
        .pipe(gulp.dest('src/public/dist/img'));
});

// Optimize svg
gulp.task('svg', function() {
    return gulp.src('assets/img/**/*.svg')
        .pipe(svgo())
        .pipe(gulp.dest('src/public/dist/img'))
});

// Watch Files For Changes
gulp.task('watch', function() {
    var root = 'assets/'
    gulp.watch(root + 'js/vendors/angular.js', ['scripts']);
    gulp.watch(root + 'js/vendors/angular-file-upload/*.js', ['scripts']);
    gulp.watch(root + 'js/vendors/*.js', ['scripts']);
    gulp.watch(root + 'js/init.js', ['scripts']);
    gulp.watch(root + 'js/app/**/*.js', ['angular']);
    gulp.watch(root + 'css/scss/**/*.scss', ['sass']);
    gulp.watch(root + 'img/**/*.jpg', ['images']);
    gulp.watch(root + 'img/**/*.png', ['images']);
    gulp.watch(root + 'img/**/*.svg', ['svg']);
});

// Default Task
gulp.task('default', ['sass', 'angular', 'scripts', 'images', 'svg']);