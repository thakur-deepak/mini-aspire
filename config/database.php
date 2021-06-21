<?php

return [

    'fetch' => PDO::FETCH_CLASS,
    'default' => env('DB_CONNECTION', 'pgsql'),
    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],
        'pgsql' => [
            'driver'   => 'pgsql',
            'url'     => env("DATABASE_URL")
        ],
        'sqlite_testing' => [
            'driver'        => 'sqlite',
            'database'  => storage_path() . '/testing.sqlite',
            'prefix'        => '',
        ],
    ],
    'migrations' => 'migrations',
        'redis' => [
            'client' => 'predis',
            'default' => [
                'url'     => env("REDIS_URL")
                ],

        ],
];
