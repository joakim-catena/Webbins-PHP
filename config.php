<?php

/**
 * You can find more information with examples in our documentation
 * at http://webbins.se/framework/documentation/configure
 */
$config = [
    /**
     * The path parameter should contain the path of your project on the web server.
     * If the framework is reachable from root, then leave the string empty.
     * @var  string
     */
    'path'      => 'Webbins-PHP',

    /**
     * Database array. The database class is built around the PDO extension. You can
     * read more about it at http://www.php.net/manual/en/book.pdo.php
     * @var  array
     */
    'database' => [
        /**
         * Connect specifies if the application should try to connect to a database.
         * Be sure to specify host, user, pass and database below.
         * @var  bool
         */
        'connect' => false,

        /**
         * The host the application should connect to.
         * @var  string
         */
        'host'    => 'localhost',

        /**
         * Specify your username.
         * @var  string
         */
        'user'    => '',

        /**
         * Specify your password.
         * @var  string
         */
        'pass'    => '',

        /**
         * Specify your database name.
         * @var  string
         */
        'db'      => '',

        /**
         * Specifies which database driver that should be used. You can find more
         * available drivers at http://www.php.net/manual/en/pdo.drivers.php.
         * @var  string
         */
        'driver'  => 'mysql',

        /**
         * Specifies which charset to use.
         * @var  string
         */
        'charset' => 'utf8'
    ],

    /**
     * Autoloading is used to load in your files without using "include" or
     * "require". It's recommended to keep the "cache" parameter turned off
     * while developing, but turn it on when you're releasing your application.
     *
     * If the cache parameter is turned on the framework will store a cached
     * file with all files in it. If you've removed or added a file since the
     * cache file was created, you need to remove the cache file and the
     * framework will create a new with all updates for you.
     * @var  array
     */
    'autoloading' => [
        /**
         * Specifies if the framework should cache the autoloading result.
         * @var  bool
         */
        'cache'         => false,

        /**
         * Contains all directories that should be autoloaded.
         * @var  array
         */
        'resources'     => ['app'],

        /**
         * Contains excluded directories.
         * @var  array
         */
        'excludes'      => [''],

        /**
         * The path where the cache file is stored.
         * @var  string
         */
        'path'          => 'app/tmp/autoloader.cache'
    ],

    /**
     * Views.
     * @var  array
     */
    'views' => [
        /**
         * The path of all your views.
         * @var  string
         */
        'path'     => 'app/views',

        /**
         * The path to store all temporary (compiled) views.
         * @var  string
         */
        'tmpPath'  => 'app/tmp/views',

        /**
         * The following classes will be available from all view files without
         * declaring them with "use" or specifying the whole namespace path.
         * @var  array
         */
        'namespaces' => [
            'Webbins\Sessions\Session',
            'Webbins\Cookies\Cookie'
        ]
    ],

    /**
     * Logs.
     * @var  array
     */
    'logs' => [
        'error' => 'logs/error.log'
    ]
];
