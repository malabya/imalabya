'use strict';

// File paths
const files = {
  imgPath: "src/assets/*",
};

// Include plugin
const imagemin = require('gulp-imagemin');

// Include gulp
const { src, dest } = require('gulp');

// Minify images.
module.exports = {
  imagemin: function() {
    return src([files.imgPath])
      .pipe(imagemin([
        imagemin.gifsicle({ interlaced: true }),
        imagemin.mozjpeg({ quality: 75, progressive: true }),
        imagemin.optipng({ optimizationLevel: 5 }),
        imagemin.svgo({
          plugins: [
            { removeViewBox: true },
            { cleanupIDs: false }
          ]
        })
      ]))
      .pipe(dest('dist/assets'));
  }
}
