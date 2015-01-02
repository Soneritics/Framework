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
namespace Framework\Web\Request;

/**
 * Abstract request class.
 * 
 * @author Jordi Jolink
 * @since 23-12-2014
 */
abstract class RequestAbstract
{
    private $data = null;

    /**
     * Get the data to parse.
     * 
     * @return array
     */
    protected abstract function getData();

    /**
     * Parse the data from the request and strip the slashes from it.
     * 
     * @param array|string $data
     * @return array|string
     */
    private function parseRequestData($data)
    {
        return is_array($data) ?
            array_map([$this, 'parseRequestData'], $data) :
            stripslashes($data);
    }

    /**
     * Set the data property with the pased request data.
     */
    private function parseData()
    {
        $data = $this->parseRequestData($this->getData());
        $this->data = $data;
    }

    /**
     * Get data from the request by name. Arrays can be used by a dot in the
     * $name variable. For example: 'User.email'
     * 
     * @param string $name
     * @return type
     */
    private function getDataFromName($name)
    {
        $parts = explode('.', $name);

        if (!empty($parts)) {
            $result = $this->data;

            foreach ($parts as $part) {
                if (!isset($result[$part])) {
                    return null;
                }

                $result = $result[$part];
            }

            return $result;
        }

        return null;
    }

    /**
     * Public getter. Parses the request data and uses the private 
     * getDataFromName function to fetch the data.
     * 
     * @param type $id
     * @return type
     */
    public function get($id = null)
    {
        if ($this->data === null) {
            $this->parseData();
        }

        if ($id === null) {
            return $this->data;
        }

        if (isset($this->data[$id])) {
            return $this->data[$id];
        }

        return $this->getDataFromName($id);
    }
}
