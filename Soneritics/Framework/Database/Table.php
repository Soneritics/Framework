<?php
/* 
 * The MIT License
 *
 * Copyright 2014 Soneritics Webdevelopment.
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
namespace Framework\Database;

use Framework\Database\Query\Delete;
use Framework\Database\Query\Insert;
use Framework\Database\Query\Select;
use Framework\Database\Query\Truncate;
use Framework\Database\Query\Update;
use Framework\Database\Query\Describe;
use Framework\Database\Query\Count;

/**
 * Table class. Corresponds to a database table and holds functions to insert,
 * update, delete and select.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  1-1-2015
 */
class Table
{
    private $name = null;
    private $table = null;
    private $columns = null;

    /**
     * Constructor. Takes the class name and sets the name and table properties.
     */
    public function __construct()
    {
        $parts = explode('\\', get_class($this));
        $name = $parts[count($parts) - 1];
        if ($name != 'Table') {
            $this->setName($name);
        }
    }

    /**
     * Set the database table from the $name property.
     */
    public function setTableFromName()
    {
        $tbl = strtolower(preg_replace('~(?!\A)(?=[A-Z]+)~', '_', $this->name));

        if (substr($tbl, -1) === 'y') {
            $tbl = substr($tbl, 0, -1) . 'ies';
        } elseif (substr($tbl, -1) === 's') {
            $tbl = substr($tbl, 0, -1) . 'ses';
        } else {
            $tbl .= 's';
        }

        $this->table = $tbl;
        return $this;
    }

    /**
     * Setter for the name.
     *
     * @param  $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setTableFromName();
        return $this;
    }

    /**
     * Returns the name of the object. When no name has been set,
     * returns the name of the table.
     *
     * @return null|string
     */
    public function getName()
    {
        if ($this->name === null) {
            return $this->getTable();
        }

        return $this->name;
    }

    /**
     * Set the table.
     *
     * @param  $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Getter for the table.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Fetch the column names of the current table.
     *
     * @return array
     */
    public function getColumns()
    {
        if ($this->columns !== null) {
            return $this->columns;
        }

        $this->columns = [];
        $describe = new Describe($this);
        foreach ($describe->execute()->all() as $column) {
            $this->columns[] = $column['Field'];
        }

        return $this->getColumns();
    }

    /**
     * Truncate the current table's data.
     *
     * @return $this
     */
    public function truncate()
    {
        new Truncate($this);
        return $this;
    }

    /**
     * Get a select object for the current table.
     *
     * @param  null $fields
     * @return Select
     */
    public function select($fields = null)
    {
        $select = new Select($this);

        if ($fields !== null) {
            $select->fields($fields);
        }

        return $select;
    }

    /**
     * Get an insert object for the current table.
     *
     * @return Insert
     */
    public function insert()
    {
        return new Insert($this);
    }

    /**
     * Get an update object for the current table.
     *
     * @return Update
     */
    public function update()
    {
        return new Update($this);
    }

    /**
     * Get a delete object for the current table.
     *
     * @return Delete
     */
    public function delete()
    {
        return new Delete($this);
    }

    /**
     * Execute a simple count query.
     *
     * @param  type $where
     * @return Count
     */
    public function count()
    {
        return new Count($this);
    }
}
