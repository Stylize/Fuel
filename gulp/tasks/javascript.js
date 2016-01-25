'use strict';

var gulp = require('gulp'),
		jshint = require('gulp-jshint'),
		sourcemaps = require('gulp-sourcemaps'),
		browserSync = require('browser-sync').get('My Gulp'),
		config = require('../config').javascript;

gulp.task('javascript', function() {
	return gulp.src(config.src)
		.pipe(sourcemaps.init())
		.pipe(jshint())
		.pipe(jshint.reporter('default'))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(config.dest))
		.pipe(browserSync.stream());
})
