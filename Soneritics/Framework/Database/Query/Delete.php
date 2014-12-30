<?php
class Delete extends QueryAbstract
{
    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType()
    {
        return 'DELETE';
    }

    /**
     * Wrapper around the setTable function for the delete class.
     *
     * @param $table
     * @return $this
     */
    public function from($table)
    {
        return $this->setTable($table);
    }
}