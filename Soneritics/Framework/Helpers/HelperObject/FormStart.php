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
 * Form's start element.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  21-12-2014
 */
class FormStart extends AbstractHelperObject
{
    /**
     * Render the object.
     *
     * @return string HTML code of the object.
     */
    public function render()
    {
        echo $this->renderHTML('<form{params}>');
    }

    /**
     * Wrapper for the `method` parameter.
     *
     * @param  string $method
     * @return $this
     */
    public function setMethod($method)
    {
        return $this->setParam('method', $method);
    }

    /**
     * Wrapper for the `action` parameter.
     *
     * @param  string $action
     * @return $this
     */
    public function setAction($action)
    {
        return $this->setParam('action', $action);
    }
}
