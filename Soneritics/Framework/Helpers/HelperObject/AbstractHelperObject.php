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
namespace Framework\Helpers\HelperObject;

/**
 * Abstract class for the HelperObject classes.
 * 
 * @author Soneritics Webdevelopment
 * @date 21-12-2014
 */
abstract class AbstractHelperObject
{
    protected $name = null,
              $params = array();

    /**
     * Render the object.
     * 
     * @return string HTML code of the object.
     */
    abstract public function render();

    /**
     * Format the attribute of an HTML object (attribute="value")
     * 
     * @param string $attribute
     * @return string
     */
    protected function formatAttribute($attribute)
    {
        return preg_replace('/[^A-Za-z0-9\-\_]/', '', $attribute);
    }

    /**
     * Format the value of an HTML object (attribute="value")
     * 
     * @param type $value
     * @return string
     */
    protected function formatValue($value)
    {
        $formattedValue = str_replace('"', '&quote;', htmlspecialchars($value));
        return sprintf('"%s"', $formattedValue);
    }

    /**
     * Render HTML from the parameters in the $markup.
     * 
     * @param string $markup
     * @return string
     */
    protected function renderHTML($markup)
    {
        if (!empty($this->params)) {
            $params = array();

            foreach ($this->params as $attribute => $value) {
                $params[] =
                    $this->formatAttribute($attribute) . '=' .
                    $this->formatValue($value);
            }

            $parameters = ' ' . implode(' ', $params);
        } else {
            $parameters = '';
        }

        return sprintf($markup, $parameters) . "\n";
    }

    /**
     * Construct the object.
     * 
     * @param string $name
     */
    public function __construct($name = null)
    {
        if ($name !== null) {
            $this->name = $name;
        }
    }

    /**
     * Set the name.
     * 
     * @param string $name
     * @return \Framework\Helpers\AbstractHelperObject
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Return the object's name.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the object's class(ses).
     * 
     * @param string $class
     * @return \Framework\Helpers\AbstractHelperObject
     */
    public function setClass($class)
    {
        return $this->setParam('class' , $class);
    }

    /**
     * Set a param.
     * 
     * @param string $class
     * @return \Framework\Helpers\FormStart
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Get a parameter value.
     * 
     * @return string
     */
    public function getParam($id)
    {
        if (isset($this->params[$id])) {
            return $this->params[$id];
        }

        return null;
    }
}