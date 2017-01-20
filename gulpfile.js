var gulp = require('gulp');
var babel = require('gulp-babel');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

var jsDashboardFiles = 'admin/assets/src/js/dashboard/**/*.js',
jsDashboardDest = 'admin/assets/dist/js';

var jsLoginFiles = 'admin/assets/src/js/login/**/*.js',
jsLoginDest = 'admin/assets/dist/js';

gulp.task('dashboard-scripts', function() {
  return gulp.src(jsDashboardFiles)
  .pipe(concat('dashboard.js'))
  .pipe(babel({presets: ['es2015']}))
  .pipe(gulp.dest(jsDashboardDest))
  .pipe(rename('dashboard.min.js'))
  .pipe(uglify())
  .pipe(gulp.dest(jsDashboardDest));
});

gulp.task('login-scripts', function() {
  return gulp.src(jsLoginFiles)
  .pipe(concat('login.js'))
  .pipe(babel({presets: ['es2015']}))
  .pipe(gulp.dest(jsLoginDest))
  .pipe(rename('login.min.js'))
  .pipe(uglify())
  .pipe(gulp.dest(jsLoginDest));
});
