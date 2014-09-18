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
namespace Soneritics\Framework\Logging;

use Soneritics\Framework\Logging\Logger\Logger;

/**
 * 
 * @author Jordi Jolink
 * @date 14-9-2014
 */
class Log
{
	private static $logger;

	/**
	 * Set the logger to use.
	 * 
	 * @param \Soneritics\Framework\Logging\Logger $logger
	 * @return \Soneritics\Framework\Logging\Log
	 */
	public static function setLogger(Logger $logger)
	{
		static::$logger = $logger;
		return $this;
	}

	/**
	 * Write a debug message to the selected logger.
	 * When no logger has been initialized, the default logger is used.
	 * 
	 * @param type $object
	 */
    public static function write($object)
	{
		if (static::$logger === null) {
			static::$logger = new Logger\Screen();
		}

		static::$logger->write($object);
		return $this;
	}
}