'use strict';

// File paths
const files = {
  scssPath: "src/scss/**/*.scss",
  jsPath: "src/scripts/**/*.js"
};

// Include gulp
const { src } = require('gulp');

// Include plugins
const stylelint = require('gulp-stylelint');
const eslint = require('gulp-eslint');

// Lint SASS & JS files.
module.exports = {
  lintScss: function() {
    return src(files.scssPath)
      .pipe(stylelint({
        reporters: [
          {
            formatter: 'string',
            console: true,
            failAfterError: true
          }
        ]
      }))
  },
  lintJs: function() {
    return src(files.jsPath)
      .pipe(eslint())
      .pipe(eslint.format())
  }
};
