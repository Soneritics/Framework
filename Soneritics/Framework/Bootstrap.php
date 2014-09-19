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
namespace Soneritics\Framework;

use \Application;

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
    private $folders;

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
        (new SplClassLoader(
            'Soneritics',
            $this->folders->get('root')
        ))->register();

        // Add the App's auto loading
        (new SplClassLoader(
            '',
            $this->folders->get('app')
        ))->register();
    }

    /**
     * Initialize the error handler.
     */
    private function initErrorHandling()
    {
        
    }

    /**
     * Start the application.
     */
    private function dispatch()
    {
        require_once($this->folders->get('app') . '/Application.php');
        $application = new Application();

        echo ' ja';
    }

    /**
     * Starts the application.
     * 
     * @param string $appPath Path of the application root (optional).
     */
    public function __construct($appPath = null)
    {
        // Initialize and register the necessary
        $this->setFolders($appPath);
        $this->initAutoLoading();
        $this->initErrorHandling();

        // Start the application
        $this->dispatch();
    }
}