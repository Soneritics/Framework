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
namespace Framework\MVC;

use Framework\Web\Request\Post;
use Framework\Web\Request\Get;
use Framework\Web\Request\Request;

/**
 * Abstract controller class, defines the lay-out of the controllers.
 * 
 * @author Jordi Jolink
 * @since  17-11-2014
 */
abstract class Controller
{
    private $request = null,
            $post = null,
            $get = null;

    /**
     * Get a value from the Post object.
     * 
     * @param  type $name
     * @return type
     */
    protected function fromPost($name = null)
    {
        if ($this->post === null) {
            $this->post = new Post;
        }

        return $this->post->get($name);
    }

    /**
     * Get a value from the Get object.
     * 
     * @param  type $name
     * @return type
     */
    protected function fromGet($name = null)
    {
        if ($this->get === null) {
            $this->get = new Get;
        }

        return $this->get->get($name);
    }

    /**
     * Get a value from the Request object.
     * 
     * @param  type $name
     * @return type
     */
    protected function fromRequest($name = null)
    {
        if ($this->request === null) {
            $this->request = new Request;
        }

        return $this->request->get($name);
    }
}
