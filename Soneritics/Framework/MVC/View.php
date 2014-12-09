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
namespace Framework\MVC;

use Framework\Exceptions\FatalException;
use Framework\Renderer\Renderer;

/**
 * 
 * 
 * @author Jordi Jolink
 * @date 17-11-2014
 */
class View
{
    private $layout = null,
            $view = null,
            $params = array();

    public function __construct($view = null)
    {
        static $layoutLoaded = false;

        if (!$layoutLoaded) {
            $layoutLoaded = true;
            $this->layout = 'default';
        }

        if ($view !== null) {
            $this->view = $view;
        }
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function getViewFile()
    {
        return $this->view;
    }

    public function setViewFile($view)
    {
        $this->view = $view;
        return $this;
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    public function setParams(array $array)
    {
        $this->params += $array;
        return $this;
    }

    private function parseParams(Renderer $renderer)
    {
        foreach ($this->params as &$param) {
            if (is_a($param, 'Framework\MVC\View')) {
                $param = $param->render($renderer);
            }
        }
    }

    public function render(Renderer $renderer)
    {
        if ($this->view === null) {
            throw new FatalException('No view file assigned.');
        }

        if (!empty($this->params)) {
            $this->parseParams($renderer);
        }

        return $renderer->render(
            $this->view,
            $this->params,
            $this->layout
        );
    }
}
