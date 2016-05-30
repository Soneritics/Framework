<?php
chdir(__DIR__);
require_once('../../vendor/autoload.php');
new Framework\Bootstrap('..');

\Framework\Logging\Log::setLogger(new Framework\Logging\Loggers\Screen(array('html' => false)));
echo "It works\n";
\Application::log('test');

$tables = \Database\Database::getTables();
foreach ($tables as $table) {
    print_r($table);
    print_r($table->getColumns());
}