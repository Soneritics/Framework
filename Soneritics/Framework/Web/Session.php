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

use SessionHandlers\SessionHandler;

/**
 * Session class for handling session storage.
 * 
 * @author Jordi Jolink
 * @since  25-12-2014
 */
class Session
{
    private $sessionObject = null;

    /**
     * Get the SessionHandler. When no session handler has been defined,
     * create one.
     * 
     * @return SessionHandler
     */
    private function getSessionObject()
    {
        if ($this->sessionObject === null) {
            $sessionHandler = \Application::getConfig()->get('Session');

            if ($sessionHandler === null) {
                $this->sessionObject = new SessionHandlers\Session;
            } else {
                $config = 
                    isset($sessionHandler['Config']) && is_array($sessionHandler['Config']) ?
                    $sessionHandler['Config'] :
                    [];

                $class = isset($sessionHandler['Handler']) ?
                    (string)$sessionHandler['Handler'] :
                    'Framework\Web\SessionHandlers\Session';

                $class = class_exists($class) ?
                    $class :
                    (
                        class_exists('Framework\Web\SessionHandlers\\' . $class) ?
                        'Framework\Web\SessionHandlers\\' . $class :
                        'Framework\Web\SessionHandlers\Session'
                    );

                    $this->sessionObject = new $class($config);
            }
        }

        return $this->sessionObject;
    }

    /**
     * Explicitly sets a SessionHandler to use.
     * 
     * @param  SessionHandler $sessionObject
     * @return \Framework\Web\Session
     */
    public function setSessionHandler(SessionHandler $sessionObject)
    {
        $this->sessionObject = $sessionObject;
        return $this;
    }

    /**
     * Updates the session object.
     * 
     * @return \Framework\Web\Session
     */
    public function update()
    {
        $this->sessionObject = new get_class($this->sessionObject);
        return $this;
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
        return $this->getSessionObject()->get($key, $default);
    }

    /**
     * Checks key existence.
     *
     * @param  $key        The index of the session.
     * @return boolean
     */
    public function has($key)
    {
        return $this->getSessionObject()->has($key);
    }

    /**
     * Sets session data.
     * 
     * @param $key    The index of the session.
     * @param $value    The value to be put into the session.
     */
    public function set($key, $value)
    {
        $this->getSessionObject()->set($key, $value);
        return $this;
    }

    /**
     * Removes session data.
     * 
     * @param $key    The index of the session.
     */
    public function delete($key)
    {
        $this->getSessionObject()->delete($key);
        return $this;
    }

    /**
     * This function clears the complete session.
     */
    public function clear()
    {
        $this->getSessionObject()->clear();
        return $this;
    }
}
