<?php
class PDOMySQL implements IDatabaseType
{
    use MySQLTrait;

    private $debug = false;
    private $pdo;

    /**
     * Set-up the database connection.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->pdo = new PDO(
            $config['dsn'],
            isset($config['user']) ? $config['user'] : null,
            isset($config['password']) ? $config['password'] : null,
            isset($config['config']) ? $config['config'] : null
        );

        if (isset($config['debug'])) {
            $this->debug = (bool)$config['debug'];
        }
    }

    /**
     * Quote a value for use in a query.
     *
     * @param $value
     * @return string
     */
    public function quote($value)
    {
        if (is_array($value)) {
            $result = array();
            foreach ($value as $single) {
                $result[] = $this->quote($single);
            }
            return sprintf(
                '(%s)',
                implode(', ', $result)
            );
        } else {
            return $this->pdo->quote($value);
        }
    }

    /**
     * Execute a query. The parameter can either be a Query(Abstract) object,
     * or a string containing a full query.
     *
     * @param $query
     * @return PDOStatement
     */
    public function query($query)
    {
        if (is_subclass_of($query, 'QueryAbstract')) {
            return $this->query($this->buildQuery($query));
        }

        return $this->pdo->query($query);
    }
}