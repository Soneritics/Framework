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
use Framework\Renderer\HtmlRenderer;

/**
 * View object. This is the object that is used in controllers to actual
 * render to the screen.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  17-11-2014
 */
class View
{
    private $layout = null;
    private $view = null;
    private $params = [];
    private $renderer = null;

    /**
     * Constructor. Optionally sets a view.
     * Also, when there is no View object that has a layout yet, this will
     * automatically load the layout.
     * @staticvar boolean $layoutLoaded
     * @param     type $view
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
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Setter for the layout.
     * @param  string $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Getter for the view file.
     * @return string
     */
    public function getViewFile()
    {
        return $this->view;
    }

    /**
     * Setter for the view file.
     * @param  string $view
     * @return $this
     */
    public function setViewFile($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Set a parameter for use in the view.
     * @param  string $name
     * @param  mixed  $value
     * @return $this
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * Set multiple parameters at once.
     * @param  array $array
     * @return $this
     */
    public function setParams(array $array)
    {
        $this->params += $array;
        return $this;
    }

    /**
     * Setter for the Renderer property. Sets the renderer to use for the view.
     * @param Renderer $renderer
     * @return \Framework\MVC\View
     */
    public function setRenderer(Renderer $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * Get the renderer for this view.
     * When no renderer has been explicitly set. use the HtmlRenderer.
     * @return \Framework\Renderer\Renderer
     */
    protected function getRenderer()
    {
        return $this->renderer === null ?
            new HtmlRenderer() :
            $this->renderer;
    }

    /**
     * Make the parameters available in the view.
     */
    private function parseParams()
    {
        if (!empty($this->params)) {
            foreach ($this->params as &$param) {
                if (is_a($param, 'Framework\MVC\View')) {
                    $param = $param->render();
                }
            }
        }
    }

    /**
     * Render the view using the $renderer Renderer.
     * @return string
     * @throws FatalException
     */
    public function render()
    {
        if ($this->view === null) {
            throw new FatalException('No view file assigned.');
        }

        $this->parseParams();

        return $this->getRenderer()->render(
            $this->view,
            $this->params,
            $this->layout
        );
    }
}
