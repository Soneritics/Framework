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
namespace Framework\Web;

/**
 * Object that loads server variables.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  1-1-2015
 */
class Server
{
    /**
     * Get a filtered variable from the $_SERVER super global.
     *
     * @param  type $id
     * @return mixed Boolean false when not found, otherwise the variable.
     */
    public function get($id)
    {
        if (isset($_SERVER[$id])) {
            return filter_var($_SERVER[$id]);
        }

        return false;
    }

    /**
     * Find out if the application is running from the command line interface.
     *
     * @return boolean
     */
    public function isCLI()
    {
        return php_sapi_name() === 'cli';
    }
}
