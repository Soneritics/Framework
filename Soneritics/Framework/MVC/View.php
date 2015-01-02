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

use Framework\Exceptions\FatalException;
use Framework\Renderer\Renderer;

/**
 * View object. This is the object that is used in controllers to actual
 * render to the screen.
 * 
 * @author Jordi Jolink
 * @since 17-11-2014
 */
class View
{
    private $layout = null,
            $view = null,
            $params = array();

    /**
     * Constructor. Optionally sets a view.
     * Also, when there is no View object that has a layout yet, this will
     * automatically load the layout.
     * 
     * 
     * @staticvar boolean $layoutLoaded
     * @param type $view
     */
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

    /**
     * Getter for the layout.
     * 
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Setter for the layout.
     * 
     * @param string $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Getter for the view file.
     * 
     * @return string
     */
    public function getViewFile()
    {
        return $this->view;
    }

    /**
     * Setter for the view file.
     * 
     * @param string $view
     * @return $this
     */
    public function setViewFile($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Set a parameter for use in the view.
     * 
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * Set multiple parameters at once.
     * 
     * @param array $array
     * @return $this
     */
    public function setParams(array $array)
    {
        $this->params += $array;
        return $this;
    }

    /**
     * MAke the parameters available in the view.
     * 
     * @param Renderer $renderer
     */
    private function parseParams(Renderer $renderer)
    {
        foreach ($this->params as &$param) {
            if (is_a($param, 'Framework\MVC\View')) {
                $param = $param->render($renderer);
            }
        }
    }

    /**
     * Render the view using the $renderer Renderer.
     * 
     * @param Renderer $renderer
     * @return string
     * @throws FatalException
     */
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
