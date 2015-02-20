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
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  23-12-2014
 */
abstract class RequestAbstract
{
    private $data = null;

    /**
     * Get the data to parse.
     *
     * @return array
     */
    abstract protected function getData();

    /**
     * Parse the data from the request and strip the slashes from it.
     *
     * @param  array|string $data
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
     * @param  string $name
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
     * @param  type $id
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

    /**
     * Get the request parts out of the data.
     * returns an array with urlencoded values that can directly be pasted
     * into a URL.
     *
     * @param  array  $data
     * @param  string $part
     * @param  array  $exclude Optional array with exclude values in the full
     *                name, eg: [data[Filter][search], data[Filter][page]]
     * @return array
     */
    private function getRequestPartsFromArray(array $data, $part = 'data', $exclude = [])
    {
        if (empty($data)) {
            return [];
        }

        $result = [];

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $result = array_merge($result, $this->getRequestPartsFromArray(
                    $v,
                    $part . "[{$k}]",
                    $exclude
                ));
            } else {
                if (!in_array("{$part}[{$k}]", $exclude)) {
                    $result[] = sprintf(
                        '%s[%s]=%s',
                        $part,
                        urlencode($k),
                        urlencode($v)
                    );
                }
            }
        }

        return $result;
    }

    /**
     * Get the request parts out of the data.
     * returns an array with urlencoded values that can directly be pasted
     * into a URL.
     *
     * @param  array $exclude Optional array with exclude values in the full
     *                        name: [data[Filter][search], data[Filter][page]]
     * @return array
     */
    public function getRequestParts(array $exclude = [])
    {
        if ($this->data === null) {
            $this->parseData();
        }

        return $this->getRequestPartsFromArray($this->data, 'data', $exclude);
    }
}
