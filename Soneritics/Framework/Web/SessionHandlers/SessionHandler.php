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
namespace Framework\Web\SessionHandlers;

/**
 * Abstract class for session handlers.
 * 
 * @author Jordi Jolink
 * @date 25-12-2014
 */
abstract class SessionHandler
{
    /**
     * Retrieve data from a session.
     *
     * @param	$key		The index of the session.
     * @param	$default	Default return value when not set.
     * @return	Any type of variable
     */
    abstract public function get($key, $default = null);

    /**
     * Checks key existence.
     *
     * @param	$key		The index of the session.
     * @return	boolean
     */
    abstract public function has($key);

    /**
     * Sets session data.
     * 
     * @param	$key	The index of the session.
     * @param	$value	The value to be put into the session.
     */
    abstract public function set($key, $value);

    /**
     * Removes session data.
     * 
     * @param	$key	The index of the session.
     */
    abstract public function delete($key);

    /**
     * This function clears the complete session.
     */
    abstract public function clear();
}
