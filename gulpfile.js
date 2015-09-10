var gulp = require('gulp');
var tar = require('gulp-tar');
var mergeStream = require('merge-stream');

gulp.task('default', function () {
    var files = gulp.src('src/files/**').pipe(tar('files.tar'));
    var templates = gulp.src('src/templates/*.tpl').pipe(tar('templates.tar'));

    mergeStream(gulp.src(['src/**/*', '!src/files{,/**}', '!src/templates{,/**}']))
        .add(files)
        .add(templates)
        .pipe(tar('de.codequake.wcf.whoWasOnline.tar'))
        .pipe(gulp.dest('./'));
});
