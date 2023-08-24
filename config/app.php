<?php
return [
    'time_zone'  => 'PRC',
    'model'      => 'index',
    'controller' => 'index',
    'action'     => 'index',
    'debug'      => true,

    'db_default' => 'db2',
    'databases'  => [
        'default' => [
            'host'     => '192.168.31.251',
            'port'     => 3306,
            'dbname'   => 'tw_test',
            'user'     => 'root',
            'password' => 'hkisoft',
            'charset'  => 'utf8mb4',
        ],
        'db2'     => [
            'host'     => '127.0.0.1',
            'port'     => 3306,
            'dbname'   => 'test1',
            'user'     => 'root',
            'password' => 'root',
            'charset'  => 'utf8mb4',
        ],
    ],

    'template'        => [
        'suffix'   => 'html',
        'view_dir' => 'views',
    ],
    //路由重寫
    'route_rewriting' => [
        '/add'   => ["/index/index/add"],
        "/edit"  => "/index/index/edit",
        '/del'   => "/index/index/del",
        '/login' => '/index/login/index',

    ],
];