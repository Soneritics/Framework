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

// Include the framework
require_once __DIR__ . '/../../Soneritics/Framework/Bootstrap.php';

/**
 * Abstract Unit Test class.
 *
 * @author Jordi Jolink
 * @since 21-2-2015
 */
abstract class FrameworkTest extends PHPUnit_Framework_TestCase
{
    /**
     * Bootstrap object.
     * @var Framework\Bootstrap
     */
    protected $bootstrap;

    /**
     * Start the framework so we have auto loading.
     */
    public function setUp()
    {
        parent::setUp();
        $this->initFramework();
    }

    /**
     * Initialize the framework by creating the Bootstrap object.
     */
    protected function initFramework()
    {
        $this->bootstrap = new Framework\Bootstrap(__DIR__);
    }
}
