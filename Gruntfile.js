module.exports = function (grunt) {

    var sourceDir = 'app/Resources/public';
    var sassDir = sourceDir + '/sass';
    var cssDir = sourceDir + '/css';
    var jsDir = sourceDir + '/js';
    var compiledCssDir = cssDir + '/compiled';
    var webDir = 'web';

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        copy: {
            fontsToWeb: {
                cwd: sourceDir + '/fonts',
                src: ['**'],
                dest: webDir + '/fonts',
                expand: true
            },
            imagesToWeb: {
                cwd: sourceDir + '/images',
                src: ['**'],
                dest: webDir + '/images',
                expand: true
            }
        },

        sass: {
            dist: {
                files: [{
                    expand: true,
                    cwd: sassDir,
                    src: ['*.scss'],
                    dest: compiledCssDir,
                    ext: '.css'
                }]
            }
        },

        uglify: {
            jquery: {
                files: [{
                    src: [
                        sourceDir + '/js/jquery.js'
                    ],
                    dest: webDir + '/js/jquery.min.js'
                }]
            },
            main: {
                files: [{
                    src: [
                        sourceDir + '/js/bootstrap.js',
                        sourceDir + '/js/bootbox.js'
                    ],
                    dest: webDir + '/js/main.min.js'
                }]
            },
            form: {
                files: [{
                    src: [
                        jsDir + '/selectize.js',
                        jsDir + '/typeahead.js',
                        jsDir + '/jquery-ui.js',
                        jsDir + '/bootstrap-colorpicker.js',
                        jsDir + '/bootstrap-datepicker.js',
                        jsDir + '/jasny-bootstrap.js',
                        jsDir + '/jquery.fine-uploader.js',
                        jsDir + '/jquery.collection.js',
                        jsDir + '/moment.js',
                        jsDir + '/autocomplete.js',
                        jsDir + '/file-input.js',
                        jsDir + '/text-editor.js',
                        jsDir + '/document.js',
                        jsDir + '/form.js',
                        jsDir + '/zipcode-typeahead.js'
                    ],
                    dest: webDir + '/js/form.min.js'
                }]
            },
            filter: {
                files: [{
                    src: [
                        jsDir + '/bootstrap-datepicker.js',
                        jsDir + '/selectize.js',
                        jsDir + '/list.js'
                    ],
                    dest: webDir + '/js/filter.min.js'
                }]
            }
        },

        cssmin: {
            main: {
                files: [{
                    src: [
                        cssDir + '/bootstrap.css',
                        cssDir + '/font-awesome.css',
                        cssDir + '/languages.min.css',
                        compiledCssDir + '/menu.css',
                        compiledCssDir + '/lavish-bootstrap.css',
                        compiledCssDir + '/layout.css',
                        compiledCssDir + '/style.css'
                    ],
                    dest: webDir + '/css/main.css',
                    expand: false
                }]
            },
            form: {
                files: [{
                    src: [
                        cssDir + '/jquery-ui.css',
                        cssDir + '/jasny-bootstrap.css',
                        cssDir + '/selectize.bootstrap3.css',
                        cssDir + '/bootstrap-colorpicker.css',
                        cssDir + '/fine-uploader-new.css',
                        cssDir + '/typeahead.css',
                        cssDir + '/datepicker3.css',
                        compiledCssDir + '/document.css',
                        compiledCssDir + '/selectize.css',
                        compiledCssDir + '/uploader.css'
                    ],
                    dest: webDir + '/css/form.css',
                    expand: false
                }]
            },
            filter: {
                files: [{
                    src: [
                        cssDir + '/selectize.bootstrap3.css',
                        cssDir + '/datepicker3.css',
                        compiledCssDir + '/selectize.css'
                    ],
                    dest: webDir + '/css/filter.css',
                    expand: false
                }]
            },
            launcher: {
                files: [{
                    src: [
                        compiledCssDir + '/launcher.css'
                    ],
                    dest: webDir + '/css/launcher.css',
                    expand: false
                }]
            }
        },
        watch: {
            scripts: {
                files: [ jsDir + '/*.js'],
                tasks: ['uglify'],
                options: {
                    spawn: false
                }
            },
            sass: {
                files: [ sassDir + '/*.sass'],
                tasks: ['sass', 'cssmin'],
                options: {
                    spawn: false
                }
            },
            css: {
                files: [ cssDir + '/*.css'],
                tasks: ['cssmin'],
                options: {
                    spawn: false
                }
            }
        }
    })
    ;

    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-ds-deploy');

    grunt.registerTask('css', ['sass', 'cssmin']);
    grunt.registerTask('js', ['uglify']);
    grunt.registerTask('default', ['js', 'css', 'copy']);

};