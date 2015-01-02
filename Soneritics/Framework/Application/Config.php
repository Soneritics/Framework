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

use Framework\Database\Database;

/**
 * Project configuration class.
 * Handles the reading and parsing of the project's configuration files.
 * 
 * @author Jordi Jolink
 * @since 27-11-2014
 */
class Config
{
	private $configs = [];

	/**
	 * Constructor, reads the configuration files from a project.
	 * 
	 * @param string $configPath
	 */
    public function __construct($configPath)
    {
        $configFiles = array_map(
			'basename',
			glob($configPath . '/config-*.php')
		);

		if (!empty($configFiles)) {
			foreach ($configFiles as $configFile) {
				$this->configs = array_replace_recursive(
					$this->configs,
					include($configPath . '/' . $configFile)
				);
			}
		}
    }

	/**
	 * Get the contents of a configuration.
	 * 
	 * @param string $key
	 * @return mixed
	 */
    public function get($key)
    {
        if (isset($this->configs[$key])) {
            return $this->configs[$key];
        } else {
            return null;
        }
    }

    /**
     * When databases are present in the configuration file, they get
     * initialized in this function.
     */
    public function setDatabases()
    {
        $databases = $this->get('Database');
        if (!empty($databases)) {
            foreach ($databases as $id => $config) {
                Database::set($id, $config);
            }
        }
    }
}