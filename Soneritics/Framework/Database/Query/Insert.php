<?php
class Insert extends QueryAbstract
{
    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType()
    {
        return 'INSERT';
    }

    /**
     * Wrapper around the setTable function for the insert class.
     *
     * @param $table
     * @return $this
     */
    public function into($table)
    {
        return $this->setTable($table);
    }
}