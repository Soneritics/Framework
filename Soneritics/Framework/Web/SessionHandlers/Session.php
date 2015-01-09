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
namespace Framework\Web\SessionHandlers;

use Framework\Web\IP;

/**
 * Session handler by using the default $_SESSION object.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  25-12-2014
 */
class Session extends SessionHandler
{
    // IP address of the user, so sessions can be secured.
    private $ip = null;

    /**
     * Constructor. Starts the session and initializes.
     *
     * @param array $config Always empty for this class.
     */
    public function __construct(array $config = array())
    {
        // Start the session
        session_start();

        // Get the user's IP address
        $ip = new IP;
        $this->ip = $ip->get();

        // Prevent Session hijacking
        if (!isset($_SESSION[$this->ip])) {
            $_SESSION[$this->ip] = [];
        }
    }

    /**
     * Destructor, closes the session.
     */
    public function __destruct()
    {
        session_write_close();
    }

    /**
     * Retrieve data from a session.
     *
     * @param  $key        The index of the session.
     * @param  $default    Default return value when not set.
     * @return Any type of variable
     */
    public function get($key, $default = null)
    {
        return isset($_SESSION[$this->ip][$key]) ?
            $_SESSION[$this->ip][$key] :
            $default;
    }

    /**
     * Checks key existence.
     *
     * @param  $key        The index of the session.
     * @return boolean
     */
    public function has($key)
    {
        return isset($_SESSION[$this->ip][$key]);
    }

    /**
     * Sets session data.
     *
     * @param $key    The index of the session.
     * @param $value    The value to be put into the session.
     */
    public function set($key, $value)
    {
        $_SESSION[$this->ip][$key] = $value;
        return $this;
    }

    /**
     * Removes session data.
     *
     * @param $key    The index of the session.
     */
    public function delete($key)
    {
        if (isset($_SESSION[$this->ip][$key])) {
            unset($_SESSION[$this->ip][$key]);
        }

        return $this;
    }

    /**
     * This function clears the complete session.
     */
    public function clear()
    {
        $_SESSION[$this->ip] = array();
        return $this;
    }
}
