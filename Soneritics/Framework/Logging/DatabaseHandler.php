<?php
/*
 * The MIT License
 *
 * Copyright 2014 Soneritics Webdevelopment.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Framework\Logging;

use Database\DatabaseConnectionFactory;
use Database\Table;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Write logging to the database.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since 1-6-2016
 */
class DatabaseHandler extends AbstractProcessingHandler
{
    /**
     * @var Table
     */
    private static $table;

    /**
     * Database id
     */
    const DATABASE = 'Logging';

    /**
     * Constructor.
     * @param array $databaseConfiguration
     * @param Table $table Log table
     * @param bool|int $level The minimum logging level at which this handler will be triggered
     * @param boolean $bubble
     */
    public function __construct(array $databaseConfiguration, Table $table, $level = Logger::DEBUG, $bubble = true)
    {
        DatabaseConnectionFactory::create(static::DATABASE, $databaseConfiguration);
        static::$table = $table;

        /* @todo Check if the table exists, otherwise create
        if (!$table->exists()) {

        }
        */

        parent::__construct($level, $bubble);
    }

    /**
     * Write a record to the database.
     * @param array $record
     */
    protected function write(array $record)
    {
        $currentDatabase = DatabaseConnectionFactory::getActiveDatabaseId();
        try {
            DatabaseConnectionFactory::select(static::DATABASE);

            $values = [
                'message' => $record['message'],
                'context' => print_r($record['context'], true),
                'level' => $record['level_name']
            ];
            static::$table->insert()->values($values)->execute();
        } catch (\Exception $e) {
            // Fail silently, as logging a logging error results in an infinite loop
        } finally {
            DatabaseConnectionFactory::select($currentDatabase);
        }
    }
}
