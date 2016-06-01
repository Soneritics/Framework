<?php
return [
    'LogHandler' => [
        new \Monolog\Handler\FirePHPHandler(\Monolog\Logger::DEBUG),
        new \Monolog\Handler\PHPConsoleHandler([], null, \Monolog\Logger::DEBUG)
    ]
];