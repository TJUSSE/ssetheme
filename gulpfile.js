var argv = require('yargs').argv;

var gulp = require('gulp');
var es = require('event-stream');
var logger = require('gulp-logger');
var plumber = require('gulp-plumber');
var watch = require('gulp-watch');
var gutil = require('gulp-util');

var jeet = require('jeet');
var rupture = require('rupture');
var autoprefixer = require('autoprefixer-stylus');

var coffee = require('gulp-coffee');
var stylus = require('gulp-stylus');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');

var printLog = function (file) {
  gutil.log(gutil.colors.yellow('Modify [' + file.event + ']: ' + file.path.substr(file.cwd.length)));
};

gulp.task('js', function () {
  return es.merge(
    // copy
    gulp.src('src/js/vendor/**/*')
      .pipe(logger())
      .pipe(plumber())
      .pipe(gulp.dest('./js/vendor/')),

    // compile
    gulp.src('src/js/**/*.coffee')
      .pipe(logger())
      .pipe(plumber())
      .pipe(sourcemaps.init())
      .pipe(coffee())
      .pipe(sourcemaps.write('.'))
      .pipe(gulp.dest('./js/'))
  );
});

gulp.task('css', function () {
  return gulp.src(['src/css/style.styl', 'src/css/typography.styl', 'src/css/webfont.styl'])
    .pipe(logger())
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(stylus({
      use: [jeet(), rupture(), autoprefixer({
        browsers: [
          'ie >= 8',
          'ie_mob >= 10',
          'chrome >= 22',
          'ff >= 30',
          'safari >= 7',
          'opera >= 23',
          'ios >= 7',
          'android >= 2.3'
        ]
      })]
    }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('./css/'));
});

gulp.task('default', function () {
  gulp.start('js');
  gulp.start('css');
  if (argv.watch) {
    gulp.start('watch');
  }
});

gulp.task('watch', function () {
  return es.merge(
    watch(['src/js/**/*'], function (file) {
      printLog(file);
      gulp.start('js');
    }),
    watch(['src/css/**/*'], function (file) {
      printLog(file);
      gulp.start('css');
    })
  );
});
