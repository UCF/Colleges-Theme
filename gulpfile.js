const browserSync = require('browser-sync').create();
const gulp = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const include = require('gulp-include');
const eslint = require('gulp-eslint');
const isFixed = require('gulp-eslint-if-fixed');
const babel = require('gulp-babel');
const rename = require('gulp-rename');
const sass = require('gulp-dart-sass');
const sassLint = require('gulp-sass-lint');
const uglify = require('gulp-uglify');
const merge = require('merge');


const configLocal = require('./gulp-config.json');
const configDefault = {
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
};
const config = merge(configDefault, configLocal);


//
// Installation of components/dependencies
//

// Web font processing
gulp.task('move-components-font-sans-serif', (done) => {
  gulp.src([
    `${config.packagesPath + config.athena.fontPath}/ucf-sans-serif-alt/*`,
    `!${config.athena.fontPath}/ucf-sans-serif-alt/generator_config.txt`
  ])
    .pipe(gulp.dest(`${config.dist.fontPath}/ucf-sans-serif-alt`));

  done();
});

gulp.task('move-components-font-condensed', (done) => {
  gulp.src([
    `${config.packagesPath + config.athena.fontPath}/ucf-condensed-alt/*`,
    `!${config.athena.fontPath}/ucf-condensed-alt/generator_config.txt`
  ])
    .pipe(gulp.dest(`${config.dist.fontPath}/ucf-condensed-alt`));

  done();
});

gulp.task('move-components-font-slab-serif', (done) => {
  gulp.src([
    `${config.packagesPath + config.athena.fontPath}/tulia/*`,
    `!${config.athena.fontPath}/tulia/generator_config.txt`
  ])
    .pipe(gulp.dest(`${config.dist.fontPath}/tulia`));

  done();
});

// Copy Font Awesome files
gulp.task('move-components-fontawesome', (done) => {
  gulp.src(`${config.packagesPath}/font-awesome/fonts/**/*`)
    .pipe(gulp.dest(`${config.dist.fontPath}/font-awesome`));

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
gulp.task('scss-lint', () => {
  return gulp.src(`${config.src.scssPath}/*.scss`)
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError());
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

gulp.task('scss-build-theme-css', () => {
  return buildCSS(`${config.src.scssPath}/style.scss`, 'style.min.css');
});

gulp.task('scss-build', gulp.parallel('scss-build-theme-css'));

// All css-related tasks
gulp.task('css', gulp.series('scss-lint', 'scss-build'));


//
// JavaScript
//

// Run eshint on js files in src.jsPath. Do not perform linting
// on vendor js files.
gulp.task('es-lint', () => {
  return gulp.src([`${config.src.jsPath}/*.js`])
    .pipe(eslint({
      fix: true
    }))
    .pipe(eslint.format())
    .pipe(isFixed(config.src.jsPath));
});

// Concat and uglify js files through babel
gulp.task('js-build', () => {
  return gulp.src(`${config.src.jsPath}/script.js`)
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
gulp.task('watch', () => {
  if (config.sync) {
    browserSync.init({
      proxy: {
        target: config.syncTarget
      }
    });
  }

  gulp.watch(`${config.src.scssPath}/**/*.scss`, gulp.series('css')).on('change', browserSync.reload);
  gulp.watch(`${config.src.jsPath}/**/*.js`, gulp.series('js')).on('change', browserSync.reload);
});


//
// Default task
//
gulp.task('default', gulp.series('components', 'css', 'js'));
