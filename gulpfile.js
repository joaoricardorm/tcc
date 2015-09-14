var gulp = require('gulp');

//auto refresh e sincronia das paginas do site
var browserSync = require('browser-sync');
     
gulp.task('browser-sync', function() {
    browserSync.init('./**', {
        proxy: 'localhost:85/tcc',
    });
});

// Minifica o CSS
var cssmin = require("gulp-cssmin");
// Remove comentários CSS
var stripCssComments = require('gulp-strip-css-comments');

gulp.task('minify-bootstrap', function(){
    gulp.src('./bootstrap/css/bootstrap-original-unminified.css')
    .pipe(stripCssComments({all: true}))
    .pipe(cssmin())
    .pipe(gulp.dest('./bootstrap/css/min/'));
	console.log('minificou!');
});

gulp.task('watch', ['minify-bootstrap'], function () { 
    gulp.watch('./bootstrap/css/bootstrap-original-unminified.css', ['minify-bootstrap']);
	console.log('modificou bootstrap');
});

gulp.task('default', ['browser-sync', 'minify-bootstrap', 'watch']);