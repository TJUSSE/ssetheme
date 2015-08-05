var gulp = require('gulp');
var es = require('event-stream');
var logger = require('gulp-logger');
var plumber = require('gulp-plumber');
var watch = require('gulp-watch');

var autoprefixer = require('gulp-autoprefixer');
var coffee = require('gulp-coffee');
var stylus = require('gulp-stylus');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');

gulp.task('js', function () {
  return es.merge(
    // copy
    gulp.src('src/js/vendor/**/*')
      .pipe(logger())
      .pipe(plumber())
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest('./js/vendor/')),

    // compile
    gulp.src('src/js/**/*.coffee')
      .pipe(logger())
      .pipe(plumber())
      .pipe(coffee())
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest('./js/'))
  );
});

gulp.task('css', function () {
  gulp.src(['src/css/style.styl', 'src/css/webfont.styl'])
    .pipe(logger())
    .pipe(plumber())
    .pipe(stylus())
    .pipe(autoprefixer())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./css/'));
});

gulp.task('watch', function () {
  return watch(['src/**/*'], function (file) {
    console.log('modified: %s', file.path);
    gulp.start('js');
    gulp.start('css');
  });
});
