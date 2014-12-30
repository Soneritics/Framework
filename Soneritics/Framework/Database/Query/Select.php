<?php
class Select extends QueryAbstract
{
    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType()
    {
        return 'SELECT';
    }

    /**
     * Wrapper around the setTable function for the select class.
     *
     * @param $table
     * @return $this
     */
    public function from($table)
    {
        return $this->setTable($table);
    }
}