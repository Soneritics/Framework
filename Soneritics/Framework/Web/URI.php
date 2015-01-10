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

use Framework\Web\Server;

/**
 * URI object. Holds a URL.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  18-12-2014
 */
class URI
{
    private $url;
    private $server;

    /**
     * Constructor, saves the url (request uri) in the $url property.
     *
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->server = new Server;
        if (!$this->server->isCLI()) {
            if ($url === null) {
                $url = explode('?', $this->server->get('REQUEST_URI'))[0];
            }

            if (empty($url)) {
                $url = '/';
            }
        }

        $this->url = $url;
    }

    /**
     * Getter for the $url property.
     *
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * Get the server, including the protocol.
     * For example: https://www.localhost
     *
     * @return string
     */
    public function getServer()
    {
        $result = 'http';

        if ($this->server->get('HTTPS') !== false) {
            $result .= 's';
        }

        $result .= '://' . $this->server->get('HTTP_HOST');
        return $result;
    }
}
