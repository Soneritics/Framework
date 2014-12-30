<?php
class Truncate extends QueryAbstract
{
    /**
     * Overwrite the constructor so the query is executed directly.
     *
     * @param string $table
     */
    public function __construct($table = null)
    {
        parent::__construct($table);
        $this->execute();
    }

    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType()
    {
        return 'TRUNCATE';
    }
}