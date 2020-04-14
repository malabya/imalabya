// Initialize modules
// Importing specific gulp API functions lets us write them below as series() instead of gulp.series()
const { src, dest, watch, series, parallel } = require("gulp");
// Importing all the Gulp-related packages we want to use
const sourcemaps = require("gulp-sourcemaps");
const sass = require("gulp-sass");
const autoprefixer = require("gulp-autoprefixer");
const mode = require("gulp-mode")();
const csscomb = require("gulp-csscomb");
const babel = require('gulp-babel');
const rename = require('gulp-rename');

// File paths
const files = {
  scssPath: "src/scss/**/*.scss",
  jsPath: "src/scripts/**/*.js"
};

// Sass task: compiles the style.scss file into style.css
function scssTask() {
  return src(files.scssPath)
    .pipe(mode.development(sourcemaps.init()))
    .pipe(mode.development(sass({ outputStyle: "expanded" })))
    .pipe(mode.production(sass({ outputStyle: "compressed" })))
    .pipe(autoprefixer("last 5 versions"))
    .pipe(mode.development(sourcemaps.write(".")))
    .pipe(mode.development(csscomb()))
    .pipe(dest("dist/css"));
}

// Helper function for renaming files
const bundleName = (file) => {
  file.basename = file.basename.replace('.es6', '');
  file.extname = '.js';
  return file;
 };

// JS Task: Lint and Uglify the JS files and process them.
function jsTask() {
  return src([files.jsPath])
    .pipe(babel({
      presets: [['env', {
        modules: false,
        useBuiltIns: true,
        targets: { browsers: ["last 2 versions", "> 1%"] },
      }]],
    }))
    .pipe(rename(file => (bundleName(file))))
    .pipe(dest("dist/js"));
}

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
function watchTask() {
  watch([files.scssPath, files.jsPath], series(parallel(scssTask, jsTask)));
}

// Export a build Gulp task to create a deployment build.
exports.build = series(scssTask, jsTask);

// Export the default Gulp task so it can be run
// Runs the scss and js tasks simultaneously
// then runs cacheBust, then watch task
exports.default = series(parallel(scssTask, jsTask), watchTask);

