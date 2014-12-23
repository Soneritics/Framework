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
namespace Framework\Request;

/**
 * Abstract request class.
 * 
 * @author Jordi Jolink
 * @date 23-12-2014
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

    private function parseRequestData($data)
    {
        return is_array($data) ?
            array_map(array($this, 'parseRequestData'), $data) :
            stripslashes($data);
    }

    private function parseData()
    {
        $data = $this->parseRequestData($this->getData());
        $this->data = $data;
    }

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
