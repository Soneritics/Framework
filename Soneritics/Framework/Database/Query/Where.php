<?php
class Where
{
    public $operator = 'AND';
    private $whereClause = array();

    /**
     * Constructor. Sets the operator.
     * Can be 'AND' or 'OR'.
     *
     * @param string $operator
     */
    public function __construct($operator = 'AND')
    {
        if (trim(strtolower($operator)) === 'or') {
            $this->operator = 'OR';
        } else {
            $this->operator = 'AND';
        }
    }

    /**
     * Add a(n extra) part to the where statement.
     *
     * @param $where
     * @return $this
     */
    public function where($where)
    {
        $this->whereClause[] = array('=', $where);
        return $this;
    }

    /**
     * Add an IN part to the where clause.
     *
     * @param $column
     * @param array $values
     * @return $this
     */
    public function in($column, array $values)
    {
        $this->whereClause[] = array('IN', $column, $values);
        return $this;
    }

    /**
     * Add a NOT IN part to the where clause.
     *
     * @param $column
     * @param array $values
     * @return $this
     */
    public function notIn($column, array $values)
    {
        $this->whereClause[] = array('NOT IN', $column, $values);
        return $this;
    }

    /**
     * Add a NOT NULL part to the where clause.
     *
     * @param $column
     * @return $this
     */
    public function notNull($column)
    {
        $this->whereClause[] = array('NOT NULL', $column);
        return $this;
    }

    /**
     * Add a LIKE part to the WHERE clause.
     *
     * @param $column
     * @param $value
     * @return $this
     */
    public function like($column, $value)
    {
        $this->whereClause[] = array('LIKE', $column, $value);
        return $this;
    }

    /**
     * Return the full where clause for the DatabaseType object to parse.
     *
     * @return array
     */
    public function getWhereClause()
    {
        return $this->whereClause;
    }

    /**
     * Get the operator.
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }
}