'use strict';

// File paths
const files = {
  scssPath: "src/scss/**/*.scss",
};

// Include gulp
const { src, dest } = require("gulp");

// Importing all the Gulp-related packages
const sourcemaps = require("gulp-sourcemaps");
const sass = require("gulp-sass");
const autoprefixer = require("gulp-autoprefixer");
const mode = require("gulp-mode")();

// Compile Sass.
module.exports = {
  styles: function () {
    return src(files.scssPath)
    .pipe(mode.development(sourcemaps.init()))
    .pipe(mode.development(sass({ outputStyle: "expanded" })))
    .pipe(mode.production(sass({ outputStyle: "compressed" })))
    .pipe(autoprefixer("last 5 versions"))
    .pipe(mode.development(sourcemaps.write(".")))
    .pipe(dest("dist/css"));
  },
};
