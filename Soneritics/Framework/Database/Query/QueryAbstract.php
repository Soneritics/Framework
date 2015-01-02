<?php
/* 
 * The MIT License
 *
 * Copyright 2014 Jordi Jolink.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Framework\Database\Query;

use Framework\Database\Database;
use Framework\Exceptions\FatalException;

/**
 * Abstract class for query objects. Every query object extends this abstract,
 * which automatically gives it full chainable query functionality.
 * 
 * @author Jordi Jolink
 * @since 1-1-2015
 */
abstract class QueryAbstract
{
    protected $table = null;
    protected $fields = '*';
    protected $joins = array();
    protected $wheres = array();
    protected $group = array();
    protected $order = array();
    protected $limit = array();

    /**
     * Constructor. Optionally sets the table name.
     *
     * @param string $table Name of the table (optional)
     */
    public function __construct($table = null)
    {
        if ($table !== null) {
            $this->table = $table;
        }
    }

    /**
     * Set the table to use.
     *
     * @param string $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Returns the current selected table.
     *
     * @return null|string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Execute the query.
     *
     * @return mixed
     * @throws FatalException
     */
    public function execute()
    {
        if ($this->table === null) {
            throw new FatalException(
                sprintf(
                    'No table defined for method `%s`.',
                    $this->getQueryType()
                )
            );
        }

        return Database::get()->query($this);
    }

    /**
     * Returns the query type.
     *
     * @return string Query type; SELECT, UPDATE, INSERT, DELETE, TRUNCATE, etc.
     */
    public abstract function getQueryType();

    /**
     * Set the fields to use in the query.
     *
     * @param $fields
     * @return $this
     */
    public function fields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Add a left join part to the query.
     *
     * @param $table
     * @param $on
     * @return $this
     */
    public function leftJoin($table, $on)
    {
        $this->joins[] = array('LEFT', $table, $on);
        return $this;
    }

    /**
     * Add a right join part to the query.
     *
     * @param $table
     * @param $on
     * @return $this
     */
    public function rightJoin($table, $on)
    {
        $this->joins[] = array('RIGHT', $table, $on);
        return $this;
    }

    /**
     * Add an inner join part to the query.
     *
     * @param $table
     * @param $on
     * @return $this
     */
    public function join($table, $on)
    {
        $this->joins[] = array('', $table, $on);
        return $this;
    }

    /**
     * Add a where clause to the query.
     *
     * @param $where
     * @return $this
     */
    public function where($where)
    {
        $this->wheres[] = $where;
        return $this;
    }

    /**
     * Add a GROUP BY part to the query.
     *
     * @param $column
     * @return $this
     */
    public function groupBy($column)
    {
        $this->group[] = $column;
        return $this;
    }

    /**
     * Order ascending on a specific $column.
     *
     * @param $column
     * @return $this
     */
    public function orderAsc($column)
    {
        $this->order[] = array($column, 'ASC');
        return $this;
    }

    /**
     * Order descending on a specific column.
     *
     * @param $column
     * @return $this
     */
    public function orderDesc($column)
    {
        $this->order[] = array($column, 'DESC');
        return $this;
    }

    /**
     * Limit the results of a query.
     *
     * @param $start
     * @param null $end
     * @return $this
     */
    public function limit($start, $end = null)
    {
        if ($end !== null) {
            $this->limit = array($start, $end);
        } else {
            $this->limit = array($start);
        }

        return $this;
    }

    /**
     * Returns an array with the different query parts (statement type, fields,
     * parameters, joins, etc.).
     *
     * @return array
     */
    public function getQueryParts()
    {
        $result = array(
            'type' => $this->getQueryType(),
            'fields' => $this->fields,
            'table' => $this->getTable()
        );

        if (!empty($this->joins)) {
            $result['join'] = $this->joins;
        }

        if (!empty($this->wheres)) {
            $result['where'] = $this->wheres;
        }

        if (!empty($this->group)) {
            $result['group'] = $this->group;
        }

        if (!empty($this->order)) {
            $result['order'] = $this->order;
        }

        if (!empty($this->limit)) {
            $result['limit'] = $this->limit;
        }

        return $result;
    }
} 