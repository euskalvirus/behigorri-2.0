// Gruntfile.js
module.exports = function (grunt) {
    // load all grunt tasks matching the ['grunt-*', '@*/grunt-*'] patterns
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        phpunit: {
            classes: {
                dir: 'app/tests'
            },
            options: {
                configuration: 'phpunit.xml',
            }
        },
        watch: {
            phpunit: {
                files: ['app/controllers/**/*.php', 'app/models/**/*.php', 'app/tests/**/*.php'],
                tasks: ['phpunit'],
                options: {
                    spawn: false,
                    atBegin: true
                }
            }
        }
    });
    grunt.registerTask('default', []);
};