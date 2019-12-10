const gulp = require("gulp");
const sass = require("gulp-sass");
const del = require("del");

gulp.task("styles", () => {
  return gulp
    .src("app/**/*.scss")
    .pipe(
      sass({
        outputStyle: "compressed"
      }).on("error", sass.logError)
    )
    .pipe(gulp.dest("./app/"));
});

gulp.task("styles:dev", () => {
  return gulp
    .src("app/**/*.scss")
    .pipe(sass().on("error", sass.logError))
    .pipe(gulp.dest("./app/"));
});

gulp.task("clean", () => {
  return del(["app/main.css"]);
});

gulp.task("default", gulp.series(["clean", "styles"]));
gulp.task("dev", gulp.series(["clean", "styles:dev"]));
