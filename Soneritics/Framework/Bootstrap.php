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

use Framework\Application\Config;
use Framework\Application\Routing;

/**
 * Bootstrap class for the Soneritics framework.
 * This class loads all the files, sets an autoloader and error handler and
 * starts the application.
 * 
 * @author Jordi Jolink
 * @date 14-9-2014
 */
class Bootstrap
{
    private $folders, $application;

    /**
     * Create a Folders object and initialize it with the root path.
     * 
     * @param string $appPath Path of the application root (optional).
     */
    private function setFolders($appPath = null)
    {
        $rootPath = dirname(dirname(__DIR__));
        require_once($rootPath . '/Soneritics/Framework/IO/Folders.php');

        $this->folders = (new IO\Folders())
            ->setFrameworkRootPath($rootPath)
            ->setAppRootPath($appPath);
    }

    /**
     * Initialize the auto loaders.
     */
    private function initAutoLoading()
    {
        // Initialize Composer's autoloader
        $vendorAutoLoader = 
                $this->folders->get('vendor') . '/autoload.php';

        if (file_exists($vendorAutoLoader)) {
            include($vendorAutoLoader);
        }

        // Include the framework's autoloader
        require($this->folders->get('framework') . '/AutoLoader.php');
        (new AutoLoader)
            ->addRootPath($this->folders->get('app'))
            ->addRootPath($this->folders->get('package'))
            ->register();
    }

    /**
     * Initialize the error handler.
     */
    private function initErrorHandling()
    {
        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     * Actual shutdown function.
     */
    public function shutdown()
    {
        $error = error_get_last();
        if (!empty($error)) {
            // Show the error
            echo '<pre>';
            print_r($error);
            echo '</pre>';
        }
    }

    /**
     * Start the application.
     */
    private function dispatch()
    {
        $this->application = new \Application();
        $config = new Config($this->folders->get('config'));

		if ($config->get('Logging') !== null) {
			$logger = $config->get('Logging');
			\Framework\Logging\Log::setLogger(
				new $logger['Logger']($logger['Config'])
			);
		}

        $this->application->run(
            $config,
            new Routing($config->get('Routing'))
        );
    }

    /**
     * Starts the application.
     * 
     * @param string $appPath Path of the application root (optional).
     */
    public function __construct($appPath = null)
    {
        // Complete output buffering
        ob_start();

        // Initialize and register the necessary
        $this->setFolders($appPath);
        $this->initAutoLoading();
        $this->initErrorHandling();

        // Start the application
        $this->dispatch();

        // When everything is done, render
        echo ob_get_clean();
    }
}