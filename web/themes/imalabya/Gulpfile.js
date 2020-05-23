// Initialize modules
// Importing specific gulp API functions lets us write them below as series()
// instead of gulp.series()

const { series, parallel } = require('gulp');

// Import the Gulp tasks.
const { styles } = require('./gulp-tasks/styles');
const { scripts } = require('./gulp-tasks/scripts');
const { imagemin } = require('./gulp-tasks/imagemin');
const { clean } = require('./gulp-tasks/clean');
const { lintScss, lintJs } = require('./gulp-tasks/lint');
const { watch } = require('./gulp-tasks/watch');

// Export a build Gulp task to create a deployment build.
exports.build = series(clean, styles, scripts, imagemin, lintScss, lintJs);
exports.watch = series(parallel(styles, scripts, imagemin), watch);
exports.clean = clean;
exports.lint = parallel(lintScss, lintJs);
