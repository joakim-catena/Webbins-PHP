<?php

/**
 * You can find more information with examples in our documentation
 * at http://webbins.se/framework/documentation/configure
 */
$config = [
    /**
     * The path parameter should contain the path of your project on the web server.
     * If the framework is reachable from root, then leave a slash (/).
     * @var  string
     */
    'path'      => '/Webbins-PHP',

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
         * Specify the host that the application should connect to.
         * @var  string
         */
        'host'     => 'localhost',

        /**
         * Specify your username.
         * @var  string
         */
        'username' => '',

        /**
         * Specify your password.
         * @var  string
         */
        'password' => '',

        /**
         * Specify your database name.
         * @var  string
         */
        'db'       => '',

        /**
         * Specifies which database driver that should be used. You can find more
         * available drivers at http://www.php.net/manual/en/pdo.drivers.php.
         * @var  string
         */
        'driver'   => 'mysql',

        /**
         * Specifies which charset to use.
         * @var  string
         */
        'charset'  => 'utf8'
    ],

    /**
     * Mail uses the Swift Mailer library to send all mails. You can find more
     * information about Swift Mailer at http://swiftmailer.org.
     * @var  array
     */
    'mail' => [
        /**
         * Connect specifies if the application should use email.
         * Be sure to specify host, user, pass and database below.
         * @var  bool
         */
        'connect' => false,

        /**
         * Specify the host that the application should connect to.
         * @var  string
         */
        'host' => '',

        /**
         * Specify the port that the application should use when connecting
         * to the host.
         * @var  int
         */
        'port' => 25,

        /**
         * This is he default mail address that all outgoing mails will be
         * sent from. It's possible to override this value by passing a
         * parameter to the mail class.
         * @var  string
         */
        'sender'   => '',

        /**
         * This is supposed to be your name or your companys name. Like the
         * default mail address, it's possible to override this value by
         * passing a parameter to the mail class.
         * @var  string
         */
        'alias'    => 'Robin Doe',

        /**
         * If you're using a email provider that need authentication you
         * should specify your username here. It's possible to override
         * this value by passing a parameter to the mail class.
         * @var  string
         */
        'username' => '',

        /**
         * Specify the password of the username. The password may also be
         * a API key.
         * @var  string
         */
        'password' => '',
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
