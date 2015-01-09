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
namespace Framework\Application;

use Framework\Web\URI;

/**
 * Routing class.
 * Handles mapping URLs to the correct module, controller and function.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  18-9-2014
 */
class Routing
{
    private $routing;
    private $route = [];

    /**
     * Constructor saved the routing array in a property.
     *
     * @param array $routing
     */
    public function __construct(array $routing = null)
    {
        if ($routing === null) {
            $routing = [];
        }

        $this->routing = $routing;
        $this->init();
    }

    /**
     * Find the route.
     *
     * @return bool Route has been found.
     */
    private function init()
    {
        // Get the current URI
        $uri = new URI;
        $url = $uri->getURL();

        // Check for full url in the routes
        if (isset($this->routing[$url])) {
            return $this->parseRoute(
                $url,
                $uri,
                $this->routing[$url]
            );
        }

        // Get wildcarded urls
        $wildcardedURLs = preg_grep('/\*/', array_keys($this->routing));
        if (empty($wildcardedURLs)) {
            return false;
        }

        // Check for partial, wildcarded url
        $parts = explode('/', $url);
        while (count($parts) > 1) {
            $check = implode('/', $parts) . '/*';
            if (in_array($check, $wildcardedURLs)) {
                return $this->parseRoute(
                    $check,
                    $uri,
                    $this->routing[$check]
                );
            }

            array_pop($parts);
        }

        // No route found
        return false;
    }

    /**
     * Store a route in the class' properties and fetch the parameters.
     *
     * @param  type  $url
     * @param  URI   $uri
     * @param  array $route
     * @return boolean
     */
    private function parseRoute($url, URI $uri, array $route)
    {
        // Fetch the parameters from the URI
        $route['Parameters'] = [];

        if (substr($url, -1) === '*') {
            $url = substr($url, 0, -1);
        }

        $stripped = substr($uri->getURL(), strlen($url));
        $strippedParts = explode('/', $stripped);

        if (!empty($strippedParts)) {
            foreach ($strippedParts as $part) {
                $var = trim($part);
                if (strlen($var) > 0) {
                    if (!isset($route['Function'])) {
                        $route['Function'] = $var;
                    } else {
                        $route['Parameters'][] = $var;
                    }
                }
            }
        }

        if (!isset($route['Function'])) {
            $route['Function'] = 'index';
        }

        // Store the route
        $this->route = $route;
        return true;
    }

    /**
     * Check if a route can be found for the given URL.
     *
     * @return bool Returns wether a route could be found.
     */
    public function canRoute()
    {
        return !empty($this->route);
    }

    /**
     * Get the property from the route or null when it doesn't exist.
     *
     * @param  string $key
     * @return mixed
     */
    private function get($key)
    {
        return isset($this->route[$key]) ? $this->route[$key] : null;
    }

    /**
     * Get the module name from the route.
     *
     * @return string
     */
    public function getModule()
    {
        return $this->get('Module');
    }

    /**
     * Get the controller name from the route.
     *
     * @return string
     */
    public function getController()
    {
        return $this->get('Controller');
    }

    /**
     * Get the function name from the route.
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->get('Function');
    }

    /**
     * Get the parameters from the route.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->get('Parameters');
    }

    /**
     * Get the request type.
     *
     * @return string
     */
    public function getRequestType()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
        } else {
            return 'Action';
        }
    }
}
