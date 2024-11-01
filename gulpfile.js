var gulp   			= require('gulp');
var sass   			= require('gulp-sass');
var clean  			= require('gulp-clean-css');
var uglify 			= require('gulp-uglify');


// Compile and watch Public assets
gulp.task('sass-public', function(){
	return gulp.src('public/src/sass/*.scss')
	.pipe(sass())
	.pipe(clean())
	.pipe(gulp.dest('public/css'))
});

gulp.task('scripts-public', function(){
	return gulp.src('public/src/js/*.js')
	.pipe(uglify())
	.pipe(gulp.dest('public/js'))
});

// Compile and watch Admin assets
gulp.task('sass-admin', function(){
	return gulp.src('admin/src/sass/*.scss')
	.pipe(sass())
	.pipe(clean())
	.pipe(gulp.dest('admin/css'))
});

gulp.task('scripts-admin', function(){
	return gulp.src('admin/src/js/*.js')
	.pipe(uglify())
	.pipe(gulp.dest('admin/js'))
});

gulp.task('watch', function(){
	// sass watching
	gulp.watch('admin/src/sass/*.scss', gulp.series('sass-admin') ); 
	gulp.watch('public/src/sass/*.scss', gulp.series('sass-public') ); 
	// JS watching
	gulp.watch('admin/src/js/*.js', gulp.series('scripts-admin') ); 
	gulp.watch('public/src/js/*.js', gulp.series('scripts-public') ); 
});

