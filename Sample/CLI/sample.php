<?php
chdir(__DIR__);
require_once('../../vendor/autoload.php');
new Framework\Bootstrap('..');

echo "It works\n";
\Application::log()->info('test');

$tables = \Database\Database::getTables();
foreach ($tables as $table) {
    print_r($table);
    print_r($table->getColumns());
}
