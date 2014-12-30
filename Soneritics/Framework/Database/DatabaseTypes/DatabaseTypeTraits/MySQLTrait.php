<?php
trait MySQLTrait
{
    /**
     * Quote a database name for use in a query.
     *
     * @param $db
     * @return string
     */
    public function quoteDatabase($db)
    {
        return $this->quoteIdentifier($db);
    }

    /**
     * Quote a table name for use in a query.
     *
     * @param $table
     * @return string
     */
    public function quoteTable($table)
    {
        return $this->quoteIdentifier((string)$table);
    }

    /**
     * Quote an identifier (column name) for use in a query.
     *
     * @param $id
     * @return string
     */
    public function quoteIdentifier($id)
    {
        return '`' . str_replace('`', '', $id) . '`';
    }

    /**
     * Prepare the fields for use in the MySQL query.
     *
     * @param array $queryParts
     * @return null|string
     */
    protected function getFields(array $queryParts)
    {
        if (isset($queryParts['fields'])) {
            if (is_array($queryParts['fields']) && !empty($queryParts['fields'])) {
                $quoted = array();

                foreach ($queryParts['fields'] as $field) {
                    $quoted[] = $this->quoteIdentifier($field);
                }

                return implode(', ', $quoted);
            } else {
                return (string)$queryParts['fields'];
            }
        }

        return null;
    }

    /**
     * Prepare the table name for use in the MySQL query.
     *
     * @param array $queryParts
     * @return null|string
     */
    protected function getTable(array $queryParts)
    {
        if (isset($queryParts['table'])) {
            if (is_a($queryParts['table'], 'Table')) {
                return sprintf(
                    'FROM %s AS %s',
                    $this->quoteTable($queryParts['table']->getTable()),
                    $this->quoteTable($queryParts['table']->getName())
                );
            } else {
                return 'FROM ' . $this->quoteTable($queryParts['table']);
            }
        }

        return null;
    }

    /**
     * Prepare the joins for use in the MySQL query.
     *
     * @param array $queryParts
     * @return array|null|string
     */
    protected function getJoins(array $queryParts)
    {
        $result = null;

        if (isset($queryParts['join']) && !empty($queryParts['join'])) {
            $result = array();

            foreach ($queryParts['join'] as $join) {
                $piece = trim($join[0] . ' JOIN') . ' ';
                $piece .= is_a($join[1], 'Table') ?
                    sprintf(
                        '%s AS %s',
                        $this->quoteTable($join[1]->getTable()),
                        $this->quoteTable($join[1]->getName())
                    ) :
                    (string)$join[1];
                $piece .= ' ON ' . $join[2];
                $result[] = $piece;
            }

            $result = implode(' ', $result);
        }

        return $result;
    }

    /**
     * Format the where clause of a MySQL query from a mixed range of types.
     *
     * @param $where
     * @param string $operator
     * @return string
     */
    private function formatWhere($where, $operator = 'AND')
    {
        if (is_string($where)) {
            return $where;
        }

        $fullWhere = array();

        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $fullWhere[] = sprintf(
                    '%s = %s',
                    $this->quoteIdentifier($key),
                    $this->quote($value)
                );
            }
        } elseif (is_a($where, 'Where')) {
            $operator = $where->getOperator();
            $whereClause = $where->getWhereClause();

            if (!empty($whereClause)) {
                foreach ($whereClause as $part) {
                    if ($part[0] === '=') {
                        $fullWhere[] = $this->formatWhere($part[1], $operator);
                    } else {
                        $q = sprintf(
                            '%s %s',
                            $this->quoteIdentifier($part[1]),
                            $part[0]
                        );

                        if (isset($part[2])) {
                            $q .= ' ' . $this->quote($part[2]);
                        }

                        $fullWhere[] = $q;
                    }
                }
            }
        }

        return implode(" {$operator} ", $fullWhere);
    }

    /**
     * Prepare the where clause for use in the MySQL query.
     *
     * @param array $queryParts
     * @return null|string
     */
    protected function getWhere(array $queryParts)
    {
        if (!isset($queryParts['where']) || empty($queryParts['where'])) {
            return null;
        }

        $where = array();
        foreach ($queryParts['where'] as $w) {
            $where[] = $this->formatWhere($w);
        }

        return 'WHERE ' . implode(' AND ', $where);
    }

    /**
     * Prepare the group for use in the MySQL query.
     *
     * @param array $queryParts
     * @return null|string
     */
    protected function getGroup(array $queryParts)
    {
        if (isset($queryParts['group'])) {
            $group = array();

            foreach ($queryParts['group'] as $column) {
                $group[] = $this->quoteIdentifier($column);
            }

            return 'GROUP BY ' . implode(', ', $group);
        }

        return null;
    }

    /**
     * Prepare the order for use in the MySQL query.
     *
     * @param array $queryParts
     * @return null|string
     */
    protected function getOrder(array $queryParts)
    {
        if (isset($queryParts['order'])) {
            $order = array();

            foreach ($queryParts['order'] as $array) {
                $order[] =
                    $this->quoteIdentifier($array[0]) .
                    (strtolower($array[1]) === 'asc' ? ' ASC' : ' DESC');
            }

            return 'ORDER BY ' . implode(', ', $order);
        }

        return null;
    }

    /**
     * Prepare the limit for use in the MySQL query.
     *
     * @param array $queryParts
     * @return null|string
     */
    protected function getLimit(array $queryParts)
    {
        if (isset($queryParts['limit']) && !empty($queryParts['limit'])) {
            $limit = array();
            foreach ($queryParts['limit'] as $int) {
                $limit[] = (int)$int;
            }

            return 'LIMIT ' . implode(', ', $limit);
        }

        return null;
    }

    /**
     * Build a query string from a QueryAbstract
     */
    public function buildQuery(QueryAbstract $query)
    {
        $queryParts = $query->getQueryParts();
        return implode(
            ' ',
            array_filter(
                array(
                    $queryParts['type'],
                    $this->getFields($queryParts),
                    $this->getTable($queryParts),
                    $this->getJoins($queryParts),
                    $this->getWhere($queryParts),
                    $this->getGroup($queryParts),
                    $this->getOrder($queryParts),
                    $this->getLimit($queryParts)
                ),
                function($var)
                {
                    return $var !== null;
                }
            )
        );
    }
} 