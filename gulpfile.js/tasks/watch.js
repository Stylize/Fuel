'use strict';

var gulp = require('gulp'),
		config = require('../config');

gulp.task('watch', ['browser-sync', 'sass', 'javascript'], function() {
	gulp.watch(config.sass.src, ['sass']);
	gulp.watch(config.javascript.src, ['javascript']);
});
