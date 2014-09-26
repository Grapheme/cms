module.exports = function(grunt) {
  grunt.initConfig({
    concat: {
      dist: {
        // the files to concatenate
        src: [
          'js/main.js',
          'js/plugins.js',
          'js/vendor/zebra_datepicker.js',
          'js/zebra_datepicker-init.js',
          'js/vendor/jquery.customSelect.min.js',
          'js/jquery.customSelect.init.js'
        ],
        // the location of the resulting JS file
        dest: 'js/index.js'
      }
    },
    less: {
      development: {
        options: {
          compress: false
        },
        files: {
          // target.css file: source.less file
          "css/main.css": "less/main.less"
        }
      }
    },
    watch: {
      styles: {
        files: ['less/**/*.less', 'js/**/*.js'], // which files to watch
        tasks: ['less', 'concat'],
        options: {
          nospawn: true
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['watch']);
};