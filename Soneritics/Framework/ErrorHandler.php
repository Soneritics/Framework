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
namespace Framework;

use Framework\Logging\Log;

/**
 * Error handler class.
 * Renders errors.
 * 
 * @author Jordi Jolink
 * @date 18-12-2014
 */
class ErrorHandler
{
	private function getErrorView(\Exception $exception)
	{
		$className = substr(get_class($exception), strlen('Framework\Exceptions\\'));
		switch ($className) {
			case 'PageNotFoundException':
			case 'PermissionDeniedException':
				return substr($className, 0, -1 * strlen('Exception'));
				break;
		}

		return 'error';
	}

	private function sendHeader(\Exception $exception)
	{
		// @todo
	}

	public function handle(\Exception $exception)
	{
		Log::write($exception);
		$this->sendHeader($exception);
		$errorRenderer = new Renderer\ErrorRenderer('error');
		echo $errorRenderer->render(
			$this->getErrorView($exception),
			array('exception' => $exception)
		);
	}
}