'use strict';

// File paths
const files = {
  scssPath: 'src/scss/**/*.scss',
  jsPath: 'src/scripts/**/*.js',
  imgPath: 'src/assets/*'
};

// Include gulp and tasks
const { watch, series, parallel } = require('gulp');
const { styles } = require('./styles');
const { scripts } = require('./scripts');

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
module.exports = {
  watch: function() {
    return watch([files.scssPath, files.jsPath], series(parallel(styles, scripts)));
  }
}
