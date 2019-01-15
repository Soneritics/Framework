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
namespace Framework;

/**
 * Error handler class.
 * Renders errors.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  18-12-2014
 */
class ErrorHandler
{
    /**
     * Get the error view.
     * @param  \Exception $exception
     * @return string
     */
    private function getErrorView(\Exception $exception)
    {
        $className = substr(get_class($exception), strlen('Framework\Exceptions\\'));
        if (!empty($className)) {
            return substr($className, 0, -1 * strlen('Exception'));
        } else {
            return 'error';
        }
    }

    /**
     * Send the correct (content type) header.
     * @param \Exception $exception
     */
    private function sendHeader(\Exception $exception)
    {
        new Headers\ContentType('text/html');
        if (isset($exception->responseCode)) {
            http_response_code($exception->responseCode);
        } else {
            http_response_code(500);
        }
    }

    /**
     * Handle the exception. Renders the error view using the class' functions.
     * @param \Exception $exception
     */
    public function handle(\Exception $exception)
    {
        \Application::log()->error(
            $exception->getMessage(), 
            [
                'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? '',
                'POST' => $_POST,
                'GET' => $_GET,
                'TRACE' => $exception->getTrace()
            ]
        );

        $this->sendHeader($exception);
        $errorRenderer = new Renderer\ErrorRenderer('Error');
        echo $errorRenderer->render(
            $this->getErrorView($exception),
            ['exception' => $exception]
        );
    }
}
