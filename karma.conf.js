const webpack = require('webpack');

module.exports = function (config) {
    config.set({
        //root path location to resolve paths defined in files and exclude
        basePath: 'assets/js',
        //files/patterns to exclude from loaded files
        exclude: [],
        //files/patterns to load in the browser
        files: [
            {pattern: 'tests/**/*.js', watched: true, served: true, included: true},
            {pattern: 'tests/fixtures/**/*.*', watched: true, included: true, served: true},
            /*parameters:
          watched: if autoWatch is true all files that have set watched to true will be watched for changes
          served: should the files be served by Karma's webserver?
          included: should the files be included in the browser using <script> tag?
          nocache: should the files be served from disk on each request by Karma's webserver? */
            /*assets:
                {pattern: '*.html', watched:true, served:true, included:false}
                {pattern: 'images/*', watched:false, served:true, included:false} */
        ],

        //executes the tests whenever one of watched files changes
        autoWatch: true,
        //if true, Karma will run tests and then exit browser
        singleRun: false,
        //if true, Karma fails on running empty test-suites
        failOnEmptyTestSuite: false,
        //reduce the kind of information passed to the bash
        logLevel: config.LOG_WARN, //config.LOG_DISABLE, config.LOG_ERROR, config.LOG_INFO, config.LOG_DEBUG

        //list of frameworks you want to use, only jasmine is installed automatically
        frameworks: ['source-map-support', 'jasmine-jquery', 'jasmine', 'fixture'],
        //list of browsers to launch and capture
        //browsers: ['Chrome'/*,'PhantomJS','Firefox','Edge','ChromeCanary','Opera','IE','Safari'*/],
        //list of reporters to use
        // browsers: ['PhantomJS'],
        browsers: ['HeadlessChrome'],

        customLaunchers: {
            HeadlessChrome: {
                base: 'ChromeHeadless',
                flags: ['--no-sandbox']
            }
        },

        reporters: ['mocha', 'kjhtml'/*,'dots','progress','spec'*/],

        //address that the server will listen on, '0.0.0.0' is default
        listenAddress: '0.0.0.0',
        //hostname to be used when capturing browsers, 'localhost' is default
        hostname: 'localhost',
        //the port where the web server will be listening, 9876 is default
        port: 9876,
        //when a browser crashes, karma will try to relaunch, 2 is default
        retryLimit: 0,
        //how long does Karma wait for a browser to reconnect, 2000 is default
        browserDisconnectTimeout: 5000,
        //how long will Karma wait for a message from a browser before disconnecting from it, 10000 is default
        browserNoActivityTimeout: 10000,
        //timeout for capturing a browser, 60000 is default
        captureTimeout: 60000,

        client: {
            //capture all console output and pipe it to the terminal, true is default
            captureConsole: true,
            //if true, Karma clears the context window upon the completion of running the tests, true is default
            clearContext: false,
            //run the tests on the same window as the client, without using iframe or a new window, false is default
            runInParent: false,
            //true: runs the tests inside an iFrame; false: runs the tests in a new window, true is default
            useIframe: true,
            jasmine: {
                //tells jasmine to run specs in semi random order, false is default
                random: false
            }
        },

        /* karma-webpack config
           pass your webpack configuration for karma
           add `babel-loader` to the webpack configuration to make the ES6+ code readable to the browser */
        webpack: {
            devtool: 'inline-source-map',

            module: {
                rules: [
                    {
                        test: /\.js$/i,
                        exclude: /(node_modules)/,
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                ]
            },
            resolve: {
                alias: {
                    'jquery': require.resolve('jquery'),
                }
            },
            plugins: [
                new webpack.ProvidePlugin({
                    $: 'jquery',
                    jQuery: 'jquery',
                    'window.jQuery': 'jquery'
                })
            ]
        },
        preprocessors: {
            './tests/fixtures/**/*.html': ['html2js'],

            //add webpack as preprocessor to support require() in test-suits .js files
            './tests/**/*.js': ['webpack'],

            '**/*.js': ['sourcemap'],
        },
        webpackMiddleware: {
            //turn off webpack bash output when run the tests
            noInfo: true,
            stats: 'errors-only'
        },

        /*karma-mocha-reporter config*/
        mochaReporter: {
            output: 'noFailures'  //full, autowatch, minimal
        }
    });
};