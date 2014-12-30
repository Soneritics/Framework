<?php
class Describe extends QueryAbstract
{
    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType()
    {
        return 'DESCRIBE';
    }
}