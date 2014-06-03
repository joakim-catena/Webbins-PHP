<?php

$config = [
    'path'      => 'Webbins-PHP',
    'views'     => 'app/views',
    'tmpViews'  => 'app/tmp/views',
    'logs' => [
        'error' => 'logs/error.log'
    ],
    'database' => [
        'connect' => false,
        'host'    => 'localhost',
        'user'    => '',
        'pass'    => '',
        'db'      => '',
        'driver'  => 'mysql',
        'charset' => 'utf8'
    ],
    'autoloading' => [
        'cacheFiles'    => false,
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
