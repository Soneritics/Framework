<?php
return [
    'LogHandler' => [
        new \Monolog\Handler\FirePHPHandler(\Monolog\Logger::DEBUG),
        new \Monolog\Handler\StreamHandler(STDOUT, \Monolog\Logger::DEBUG),
        new \Framework\Logging\DatabaseHandler([
                'type' => 'PDOMySQL',
                'dsn' => 'mysql:dbname=sandbox;host=localhost',
                'user' => 'sandbox',
                'password' => 'sandbox',
                'debug' => true
            ],
            (new \Database\Table)->setName('Log')
        )
    ]
];
