module.exports = function(grunt)
{
	// Task Configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		copy: {
			build: {
				files: [
					{expand: true, cwd: 'src', src: ['**'], dest: 'build', dot: true},
				]
			}
		},
		clean: {
			build:   { src: [ 'build' ] },
			release: { src: [ 'docs/phpdoc-cache-*' ] }
		},
		mkdir: {
			build: {
				options: { }
			}
		},
	});

	// Task Loading
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-mkdir');

	// Task Definitions
	grunt.registerTask('build', 'Builds the project into ./build',
		[ 'clean:build', 'copy:build' ]
	);
	grunt.registerTask('release', 'Builds the project for release', 
		[ 'clean:build', 'clean:release', 'copy:build' ]
	);

};
