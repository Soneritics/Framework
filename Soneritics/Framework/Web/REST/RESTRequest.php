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
namespace Framework\Web\REST;

use Framework\Logging\Log;

/**
 * REST Request class.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  5-2-2015
 */
abstract class RESTRequest
{
    // Possible POST values
    private $post = [];

    // Possible headers
    private $headers = [];

    // URL tto make the request to
    private $url;

    // The request type
    private $requestType = 'GET';

    /**
     * Parse the data from the webserver and return it as an array.
     * @param string $data
     * @return array
     */
    abstract protected function parse($data);

    /**
     * Get the content type.
     * @return string For example: application/json
     */
    abstract protected function getContentType();

    /**
     * Set the URL to send the request to.
     * @param string $url
     * @return \Framework\Web\REST\RESTRequest
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set the request type.
     * @param type $requestType
     * @return \Framework\Web\REST\RESTRequest
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
        return $this;
    }

    /**
     * Add a header.
     * @param string $header
     * @return \Framework\Web\REST\RESTRequest
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * Add post values.
     * @param type $key
     * @param type $value
     * @return \Framework\Web\REST\RESTRequest
     */
    public function addPostValue($key, $value)
    {
        if ($this->requestType === 'GET') {
            $this->requestType = 'POST';
        }

        $this->post[$key] = $value;
        return $this;
    }

    /**
     * Execute the request and return the value.
     * @return array
     */
    public function request()
    {
        Log::write('RESTRequest: ' . $this->getContentType());
        Log::write('RESTRequest: ' . $this->url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->requestType);

        $this->headers[] = 'Content-Type: ' . $this->getContentType();

        if (!empty($this->post)) {
            $dataString = json_encode($this->post);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $this->headers[] = 'Content-Length: ' . strlen($dataString);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        foreach ($this->headers as $header) {
            Log::write("RESTRequest header: {$header}");
        }

        $data = curl_exec($ch);
        curl_close($ch);

        Log::write("RESTRequest result: {$data}");

        return $this->parse($data);
    }
}
