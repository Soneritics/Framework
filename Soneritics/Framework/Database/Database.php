<?php
class Database
{
    private static $databaseConnections = array();
    private static $activeDatabaseConnection = null;

    /**
     * Create a database connection.
     *
     * @param array $config
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
     * @param array $config
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
     * @param null $id
     * @return mixed
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