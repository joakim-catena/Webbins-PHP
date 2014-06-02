<?php

$config = [
    'path'      => 'Webbins-PHP',
    'views'     => 'app/views',
    'tmpViews'  => 'app/tmp/views',
    'logs' => [
        'error' => 'logs/error.log'
    ],
    'database' => [
        'connect' => true,
        'host'    => 'localhost',
        'user'    => 'robin',
        'pass'    => 'robin',
        'db'      => 'robin_rabattrea',
        'driver'  => 'mysql',
        'charset' => 'utf8'
    ],
    'autoloading' => [
        'cacheFiles'    => true,
        'path'          => 'app/tmp/autoloader.cache',
        'resources'     => ['app/'],
        'excludes'      => ['']
    ],
    'namespaces' => [
        'Webbins\Database\DB',
        'Webbins\Sessions\Session',
        'Webbins\Cookies\Cookie'
    ]
];
