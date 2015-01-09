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
namespace Framework\Database\Query;

/**
 * Insert query class.
 * 
 * @author Jordi Jolink
 * @since  1-1-2015
 */
class Insert extends QueryAbstract
{
    // Set mode to execute
    protected $queryType = self::MODE_EXECUTE;

    /**
     * Get the query type.
     *
     * @return string
     */
    public function getQueryType()
    {
        return 'INSERT INTO';
    }

    /**
     * Wrapper around the setTable function for the insert class.
     *
     * @param  $table
     * @return $this
     */
    public function into($table)
    {
        return $this->setTable($table);
    }

    /**
     * Override the default execute function.
     * 
     * @return int Last insert ID.
     */
    public function execute()
    {
        $result = parent::execute();
        return \Framework\Database\Database::get()->lastInsertId();
    }
}
