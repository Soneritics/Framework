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
use Framework\Application\Routing;
use Framework\MVC\View;

/**
 * Mandatory Application class. This class is specifically formatted for 
 * unit testing.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since 21-2-2015
 */
class Application extends Framework\Application\Application
{
    /**
     * Function after the contoller has run.
     * @param Routing $router
     */
    protected function afterRun(Routing $router)
    {
    }

    /**
     * 
     * Function before the application runs.
     * @param Routing $router
     */
    protected function beforeRun(Routing $router)
    {
    }

    /**
     * Function that gets executed before the view renders.
     * @param View $view
     */
    protected function beforeRender(View $view)
    {
    }

    /**
     * Check if the application can run.
     * @param Routing $router
     * @return boolean
     */
    protected function canRun(Routing $router)
    {
        return false;
    }
}