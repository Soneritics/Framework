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
namespace Soneritics\Framework\Application;

use Soneritics\Framework\IO\Folders;

/**
 * Main Application class.
 * 
 * @author Jordi Jolink
 * @date 18-9-2014
 */
class Application
{
	private $application;
	private $folders;

	/**
	 * Constructor starts the application.
	 * 
	 * @param string $application
	 */
	public function __construct($application)
	{
		// Set the properties
		$this->application = $application;
		$this->folders = new Folders();

		// Validate all module files
		$this->validate();

		// Create, set-up and start the controller
		// @todo
	}

	/**
	 * Check if all the module's files can be loaded.
	 * 
	 * @throws \Soneritics\Framework\Exceptions\FatalException
	 * @throws Exception
	 */
	private function validate()
	{
		
	}

	private function getConfigs()
	{
		
	}
}