"use strict";

// Load plugins
const autoprefixer = require("gulp-autoprefixer");
const browsersync = require("browser-sync").create();
const cleanCSS = require("gulp-clean-css");
const del = require("del");
const gulp = require("gulp");
const php = require("gulp-connect-php");
const header = require("gulp-header");
const merge = require("merge-stream");
const plumber = require("gulp-plumber");
const rename = require("gulp-rename");
const sass = require("gulp-sass");
const uglify = require("gulp-uglify-es").default;

// Load package.json for banner
const pkg = require('./package.json');

// Set the banner content
const banner = ['/*!\n',
  ' * Start Bootstrap - <%= pkg.title %> v<%= pkg.version %> (<%= pkg.homepage %>)\n',
  ' * Copyright 2013-' + (new Date()).getFullYear(), ' <%= pkg.author %>\n',
  ' * Licensed under <%= pkg.license %> (https://github.com/BlackrockDigital/<%= pkg.name %>/blob/master/LICENSE)\n',
  ' */\n',
  '\n'
].join('');

// BrowserSync

function browserSync(done) {
  browsersync.init({
    server: {
      baseDir: "./public"
    }
  });
  done();
}

// BrowserSync reload
function browserSyncReload(done) {
  browsersync.reload();
  done();
}

// Clean vendor
function clean() {
  return del(["./vendor/"]);
}

// Bring third party dependencies from node_modules into vendor directory
function modules() {
  // sweetAlert 2 JS
  var b4ToggleJS = gulp.src('./node_modules/bootstrap4-toggle/js/*.js')
    .pipe(gulp.dest('./public/vendor/bootstrap4-toggle/js'));
  // sweetAlert 2 CSS
  var b4ToggleCSS = gulp.src('./node_modules/bootstrap4-toggle/css/*.css')
    .pipe(gulp.dest('./public/vendor/bootstrap4-toggle/css'));
  // sweetAlert 2 JS
  var sweetAlertJS = gulp.src('./node_modules/sweetalert2/dist/*.js')
    .pipe(gulp.dest('./public/vendor/sweetalert2/js'));
  // sweetAlert 2 CSS
  var sweetAlertCSS = gulp.src('./node_modules/sweetalert2/dist/*.css')
    .pipe(gulp.dest('./public/vendor/sweetalert2/css'));
  // Bootstrap JS
  var bootstrapJS = gulp.src('./node_modules/bootstrap/dist/js/*')
    .pipe(gulp.dest('./public/vendor/bootstrap/js'));
  // Bootstrap SCSS
  var bootstrapSCSS = gulp.src('./node_modules/bootstrap/scss/**/*')
    .pipe(gulp.dest('./public/vendor/bootstrap/scss'));

  // Select2 JS
  var select2JS = gulp.src('./node_modules/select2/dist/js/*')
    .pipe(gulp.dest('./public/vendor/select2/js'));
  // Select2 SCSS
  var select2CSS = gulp.src('./node_modules/select2/dist/css/*')
    .pipe(gulp.dest('./public/vendor/select2/css'));
  // ChartJS
  var chartJS = gulp.src('./node_modules/chart.js/dist/*.js')
    .pipe(gulp.dest('./public/vendor/chart.js'));
  // dataTables
  var dataTables = gulp.src([
    './node_modules/datatables.net/js/*.js',
    './node_modules/datatables.net-bs4/js/*.js',
    './node_modules/datatables.net-bs4/css/*.css'
  ])
    .pipe(gulp.dest('./public/vendor/datatables'));
  // Font Awesome
  var fontAwesome = gulp.src('./node_modules/@fortawesome/**/*')
    .pipe(gulp.dest('./public/vendor'));
  // jQuery Easing
  var jqueryEasing = gulp.src('./node_modules/jquery.easing/*.js')
    .pipe(gulp.dest('./public/vendor/jquery-easing'));
  // jQuery
  var jquery = gulp.src([
    './node_modules/jquery/dist/*',
    '!./node_modules/jquery/dist/core.js'
  ])
    .pipe(gulp.dest('./public/vendor/jquery'));
  return merge(b4ToggleJS, b4ToggleCSS, sweetAlertJS, sweetAlertCSS, bootstrapJS, bootstrapSCSS, chartJS, dataTables,
    fontAwesome, jquery, jqueryEasing, select2JS, select2CSS);
}

// CSS task
function css() {
  return gulp
    .src("./public/assets/scss/**/*.scss")
    .pipe(plumber())
    .pipe(sass({
      outputStyle: "expanded",
      includePaths: "./node_modules",
    }))
    .on("error", sass.logError)
    .pipe(autoprefixer({
      cascade: false
    }))
    .pipe(header(banner, {
      pkg: pkg
    }))
    .pipe(gulp.dest("./public/assets/css"))
    .pipe(rename({
      suffix: ".min"
    }))
    .pipe(cleanCSS())
    .pipe(gulp.dest("./public/assets/css"))
    .pipe(browsersync.stream());
}

// JS task
function js() {
  return gulp
    .src([
      './public/assets/js/*.js',
      '!./public/assets/js/*.min.js',
    ])
    .pipe(uglify())
    .pipe(header(banner, {
      pkg: pkg
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('./public/assets/js'))
    .pipe(browsersync.stream());
}
// JS task
function js_functions() {
  return gulp
    .src([
      './public/functions/*.js',
      '!./public/functions/*.min.js',
    ])
    .pipe(uglify())
    .pipe(header(banner, {
      pkg: pkg
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('./public/functions'))
    .pipe(browsersync.stream());
}

// Watch files
function watchFiles() {
  gulp.watch("./public/assets/scss/**/*", css);
  gulp.watch(["./public/assets/js/**/*", "!./public/assets/js/**/*.min.js"], js);
  gulp.watch(["./public/funtions/*", "!./public/funtions/*.min.js"], js_functions);
}

// Define complex tasks
const vendor = gulp.series(clean, modules);
const build = gulp.series(vendor, gulp.parallel(css, js));
const watch = gulp.series(build, gulp.parallel(watchFiles, browserSync));

// Export tasks
exports.css = css;
exports.js = js;
exports.js_functions = js_functions;
exports.clean = clean;
exports.vendor = vendor;
exports.build = build;
exports.watch = watch;
exports.default = build;
