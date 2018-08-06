var browserSync = require('browser-sync').create(),
    gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    cleanCSS = require('gulp-clean-css'),
    include = require('gulp-include'),
    eslint = require('gulp-eslint'),
    isFixed = require('gulp-eslint-if-fixed'),
    babel = require('gulp-babel'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    scsslint = require('gulp-scss-lint'),
    uglify = require('gulp-uglify'),
    merge = require('merge');


var configLocal = require('./gulp-config.json'),
    configDefault = {
      src: {
        scssPath: './src/scss',
        jsPath:   './src/js'
      },
      dist: {
        cssPath:  './static/css',
        jsPath:   './static/js',
        fontPath: './static/fonts'
      },
      athena: {
        fontPath: '/ucf-athena-framework/dist/fonts',
        scssPath: '/ucf-athena-framework/src/scss',
        jsPath:   '/ucf-athena-framework/dist/js'
      },
      packagesPath: './node_modules',
      sync: false,
      syncTarget: 'http://localhost/'
    },
    config = merge(configDefault, configLocal);


//
// Installation of components/dependencies
//

// Web font processing
gulp.task('move-components-font-sans-serif', function(done) {
  gulp.src([
    config.packagesPath + config.athena.fontPath + '/ucf-sans-serif-alt/*',
    '!' + config.athena.fontPath + '/ucf-sans-serif-alt/generator_config.txt'
  ])
    .pipe(gulp.dest(config.dist.fontPath + '/ucf-sans-serif-alt'));

  done();
});

gulp.task('move-components-font-condensed', function(done) {
  return gulp.src([
    config.packagesPath + config.athena.fontPath + '/ucf-condensed-alt/*',
    '!' + config.athena.fontPath + '/ucf-condensed-alt/generator_config.txt'
  ])
    .pipe(gulp.dest(config.dist.fontPath + '/ucf-condensed-alt'));

  done();
});

gulp.task('move-components-font-slab-serif', function(done) {
  return gulp.src([
    config.packagesPath + config.athena.fontPath + '/tulia/*',
    '!' + config.athena.fontPath + '/tulia/generator_config.txt'
  ])
    .pipe(gulp.dest(config.dist.fontPath + '/tulia'));

  done();
});

// Copy Font Awesome files
gulp.task('move-components-fontawesome', function(done) {
  gulp.src(config.packagesPath + '/font-awesome/fonts/**/*')
   .pipe(gulp.dest(config.dist.fontPath + '/font-awesome'));

  done();
});

// Run all component-related tasks
gulp.task('components', gulp.parallel(
  'move-components-fontawesome',
  'move-components-font-sans-serif',
  'move-components-font-condensed',
  'move-components-font-slab-serif'
));


//
// CSS
//

// Lint scss files
gulp.task('scss-lint', function() {
  return gulp.src(config.src.scssPath + '/*.scss')
    .pipe(scsslint({
      'maxBuffer': 400 * 1024  // default: 300 * 1024
    }));
});

// Compile scss files
function buildCSS(src, filename, dest) {
  dest = dest || config.dist.cssPath;

  return gulp.src(src)
    .pipe(sass({
      includePaths: [config.src.scssPath, config.packagesPath]
    })
      .on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(autoprefixer({
      // Supported browsers added in package.json ("browserslist")
      cascade: false
    }))
    .pipe(rename(filename))
    .pipe(gulp.dest(dest))
    .pipe(browserSync.stream());
}

gulp.task('scss-build-theme-css', function() {
  return buildCSS(config.src.scssPath + '/style.scss', 'style.min.css');
});

gulp.task('scss-build', gulp.parallel('scss-build-theme-css'));

// All css-related tasks
gulp.task('css', gulp.series('scss-lint', 'scss-build'));


//
// JavaScript
//

// Run eshint on js files in src.jsPath. Do not perform linting
// on vendor js files.
gulp.task('es-lint', function() {
  return gulp.src([config.src.jsPath + '/*.js'])
    .pipe(eslint({ fix: true }))
    .pipe(eslint.format())
    .pipe(isFixed(config.src.jsPath));
});

// Concat and uglify js files through babel
gulp.task('js-build', function() {
  return gulp.src(config.src.jsPath + '/script.js')
    .pipe(include({
      includePaths: [config.packagesPath, config.src.jsPath]
    }))
      .on('error', console.log)
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename('script.min.js'))
    .pipe(gulp.dest(config.dist.jsPath));
});

// All js-related tasks
gulp.task('js', gulp.series('es-lint', 'js-build'));


//
// Rerun tasks when files change
//
gulp.task('watch', function() {
  if (config.sync) {
    browserSync.init({
        proxy: {
          target: config.syncTarget
        }
    });
  }

  gulp.watch(config.src.scssPath + '/**/*.scss', gulp.series('css')).on('change', browserSync.reload);
  gulp.watch(config.src.jsPath + '/**/*.js', gulp.series('js')).on('change', browserSync.reload);
});


//
// Default task
//
gulp.task('default', gulp.series('components', 'css', 'js'));
