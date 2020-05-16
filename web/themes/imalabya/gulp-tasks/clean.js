'use strict';

// Include plugins
const del = require('gulp-clean');

// Include gulp
const { src } = require('gulp');

// Clean dist file.
module.exports = {
  clean: function() {
    return src('dist', { read: false }).pipe(del());
  },
};
