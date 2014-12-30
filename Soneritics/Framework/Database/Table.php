<?php
class Table
{
    private $name = null;
    private $table = null;

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
        } else {
            $tbl .= 's';
        }

        $this->table = $tbl;
        return $this;
    }

    /**
     * Setter for the name.
     *
     * @param $name
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
     * @param $table
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
        // @todo: DESCRIBE query
        return array();
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
     * @param null $fields
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
}