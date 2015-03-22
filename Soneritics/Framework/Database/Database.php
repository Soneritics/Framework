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
namespace Framework\Database;

use Framework\Database\DatabaseTypes\IDatabaseType;
use Framework\Exceptions\FatalException;

/**
 * Database class for connecting to the database.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  1-1-2015
 */
class Database
{
    private static $databaseConnections = [];
    private static $activeDatabaseConnection = null;

    /**
     * Create a database connection.
     *
     * @param  array $config
     * @return IDatabaseType
     */
    private static function createDatabaseConnection(array $config)
    {
        return new $config['type']($config);
    }

    /**
     * Set a database connection.
     *
     * @param string $id
     * @param array  $config
     */
    public static function set($id, array $config)
    {
        static::$databaseConnections[$id] =
            static::createDatabaseConnection($config);

        if (static::$activeDatabaseConnection === null) {
            static::$activeDatabaseConnection = $id;
        }
    }

    /**
     * Get a database connection.
     *
     * @param  null $id
     * @return IDatabaseType
     * @throws FatalException
     */
    public static function get($id = null)
    {
        if ($id === null && static::$activeDatabaseConnection === null) {
            throw new FatalException('No databases defined.');
        }

        if ($id === null) {
            return static::$databaseConnections[static::$activeDatabaseConnection];
        } else {
            static::$activeDatabaseConnection = $id;
            return static::$databaseConnections[$id];
        }
    }

    /**
     * Select the currently active database.
     *
     * @param string $id
     */
    public static function select($id)
    {
        static::get($id);
    }
}
