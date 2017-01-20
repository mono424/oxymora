var gulp = require('gulp');
var babel = require('gulp-babel');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');
var autoprefixer = require('gulp-autoprefixer');

// JS OF DASHBOARD
var jsDashboardFiles = 'admin/assets/src/dashboard/js/**/*.js',
jsDashboardDest = 'admin/assets/dist/js';

// CSS OF DASHBOARD
var cssDashboardFiles = 'admin/assets/src/dashboard/css/**/*.css',
cssDashboardDest = 'admin/assets/dist/css';

// JS OF LOGIN
var jsLoginFiles = 'admin/assets/src/login/js/**/*.js',
jsLoginDest = 'admin/assets/dist/js';

// CSS OF DASHBOARD
var cssLoginFiles = 'admin/assets/src/login/css/**/*.css',
cssLoginDest = 'admin/assets/dist/css';

// CSS OF ADDON
var cssAddonFiles = 'admin/assets/src/addon/css/**/*.css',
cssAddonDest = 'admin/assets/dist/css';


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

gulp.task('dashboard-css', function(){
  return gulp.src(cssDashboardFiles)
  .pipe(cleanCSS())
  .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
  .pipe(concat('dashboard.min.css'))
  .pipe(gulp.dest(cssDashboardDest));
});

gulp.task('login-css', function(){
  return gulp.src(cssLoginFiles)
  .pipe(cleanCSS())
  .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
  .pipe(concat('login.min.css'))
  .pipe(gulp.dest(cssLoginDest));
});

gulp.task('addon-css', function(){
  return gulp.src(cssAddonFiles)
  .pipe(cleanCSS())
  .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
  .pipe(concat('addon.min.css'))
  .pipe(gulp.dest(cssAddonDest));
});
