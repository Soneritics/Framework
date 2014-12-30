<?php
class Update extends QueryAbstract
{
    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType()
    {
        return 'UPDATE';
    }
}