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

/**
 * Test the AutoLoader class.
 *
 * @author Jordi Jolink
 * @since 1-1-2015
 */
class AutoLoaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test if the framework's Application class can be autoloaded.
     */
    public function testFrameworkAutoloadApplication()
    {
        $this->assertTrue(
            class_exists('Framework\Application\Application'),
            'Test if the framework\'s Application class can be autoloaded.'
        );
    }

    /**
     * Test if the `application` can be autoloaded.
     */
    public function testApplicationAutoloadApplication()
    {
        $this->assertTrue(
            class_exists('\Application'),
            'Test if application\'s Application class can be autoloaded.'
        );
    }
}
