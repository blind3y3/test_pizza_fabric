<?php

return
    [
        'paths'         => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/src/db/migrations',
            'seeds'      => '%%PHINX_CONFIG_DIR%%/src/db/seeds'
        ],
        'environments'  => [
            'default_migration_table' => 'phinxlog',
            'default_environment'     => 'development',
            'development'             => [
                'adapter' => 'mysql',
                'host'    => 'mysql',
                'name'    => '',
                'user'    => '',
                'pass'    => '',
                'port'    => '3306',
                'charset' => 'utf8',
            ],
        ],
        'version_order' => 'creation'
    ];
