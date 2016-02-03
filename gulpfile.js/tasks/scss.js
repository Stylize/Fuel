'use strict';

var gulp = require('gulp'),
		sass = require('gulp-sass'),
		sourcemaps = require('gulp-sourcemaps'),
		browserSync = require('browser-sync').get('My Gulp'),
		notify = require('../library/notify'),
		plumber = require('gulp-plumber'),
		autoprefixer = require('gulp-autoprefixer'),
		config = require('../config').sass;

gulp.task('sass', function() {
	return gulp.src(config.src)
		.pipe(sourcemaps.init())
		.pipe(plumber({errorHandler: notify}))
		.pipe(sass({ includePaths: config.includePaths }))
		.pipe(autoprefixer({ browsers: ['last 2 version'] }))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(config.dest))
		.pipe(browserSync.stream());
});

gulp.task('sass:prod', function() {
	return gulp.src(config.src)
		.pipe(sass({ includePaths: config.includePaths, outputStyle: 'compressed' }))
		.on('error', sass.logError)
		.pipe(autoprefixer({ browsers: ['last 2 version'] }))
		.pipe(gulp.dest(config.dest));
});
