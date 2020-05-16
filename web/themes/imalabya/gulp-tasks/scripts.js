"use strict";

// File paths
const files = {
  jsPath: "src/scripts/**/*.js",
};

// Include gulp
const { src, dest } = require("gulp");

// Importing all the Gulp-related packages
const babel = require("gulp-babel");
const rename = require("gulp-rename");

// Helper function for renaming files
const bundleName = (file) => {
  file.basename = file.basename.replace(".es6", "");
  file.extname = ".js";
  return file;
};

// Compile scripts
module.exports = {
  scripts: function() {
    return src([files.jsPath])
      .pipe(
        babel({
          presets: [
            [
              "env",
              {
                modules: false,
                useBuiltIns: true,
                targets: { browsers: ["last 2 versions", "> 1%"] },
              },
            ],
          ],
        })
      )
      .pipe(rename((file) => bundleName(file)))
      .pipe(dest("dist/js"));
  },
};
