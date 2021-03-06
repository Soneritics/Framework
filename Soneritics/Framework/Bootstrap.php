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

use Database\Debug\ExecutedQuery;
use Framework\Application\Config;
use Framework\Application\Routing;

/**
 * Bootstrap class for the Soneritics framework.
 * This class loads all the files, sets an autoloader and error handler and
 * starts the application.
 *
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  14-9-2014
 */
class Bootstrap
{
    private $folders;
    private $application;

    /**
     * Create a Folders object and initialize it with the root path.
     *
     * @param string $appPath Path of the application root (optional).
     */
    private function setFolders($appPath = null)
    {
        $rootPath = dirname(dirname(__DIR__));
        require_once $rootPath . '/Soneritics/Framework/IO/Folders.php';

        $this->folders = (new IO\Folders())
            ->setFrameworkRootPath($rootPath)
            ->setAppRootPath($appPath);
    }

    /**
     * Initialize the auto loaders.
     */
    private function initAutoLoading()
    {
        // Initialize Composer's autoloaders
        foreach (['framework-vendor', 'vendor'] as $folder) {
            $vendorAutoLoader =
                $this->folders->get($folder) . '/autoload.php';

            if (file_exists($vendorAutoLoader)) {
                include $vendorAutoLoader;
            }
        }

        // Include the framework's autoloader
        require_once $this->folders->get('framework') . '/AutoLoader.php';
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
        set_error_handler([$this, 'error']);
        set_exception_handler([$this, 'exception']);
    }

    /**
     * Initialize logging of database queries when using the Soneritics\Database project.
     */
    private function initDatabaseLogging()
    {
        if (class_exists('Database\Debug')) {
            \Database\Debug::addSubscriber(function(ExecutedQuery $query) {
                \Application::log()->debug(
                    sprintf(
                        "Query with type `%s` took %f sec:\n%s",
                        $query->getType(),
                        $query->getExecutionTime(),
                        $query->getQuery()
                    )
                );
            });
        }
    }

    /**
     * When everything is done, render.
     */
    public function shutdown()
    {
        echo ob_get_clean();
    }

    /**
     * Error handler, invoked by the set_error_handler function.
     * Makes sure exceptions are raised when PHP catchable errors occur.
     */
    public function error($errno, $errstr, $errfile, $errline)
    {
        if (in_array($errno, [E_ERROR, E_USER_ERROR])) {
            $log = \Application::log();
            if (!empty($log)) {
                $lastError = error_get_last();
                $log->critical(
                    "Fatal error\n" . print_r($lastError, true),
                    empty($lastError) ? debug_backtrace() : $lastError
                );
            }
        } elseif ($errno === E_RECOVERABLE_ERROR) {
            throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
        }

        return false;
    }

    /**
     * Log uncaught exceptions.
     * @param $exception
     */
    public function exception($exception)
    {
        $log = \Application::log();
        if (!empty($log)) {
            $log->error($exception, $exception->getTrace());
        }

        throw $exception;
    }

    /**
     * Start the application.
     */
    private function dispatch()
    {
        register_shutdown_function([$this, 'shutdown']);

        $this->application = new \Application();
        $config = new Config($this->folders->get('config'));
        $config->setDatabases();

        if ($config->get('LogHandler') !== null) {
            $handlers = $config->get('LogHandler');
            foreach ($handlers as $handler) {
                $this->application->log()->pushHandler($handler);
            }
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

        try {
            // Initialize and register the necessary
            $this->setFolders($appPath);
            $this->initAutoLoading();
            $this->initErrorHandling();
            $this->initDatabaseLogging();

            // Start the application
            $this->dispatch();
        } catch (\Exception $e) {
            try {
                $errorHandler = new ErrorHandler;
                $errorHandler->handle($e);
            } catch (\Exception $ex) {
                http_response_code(500);
                echo $ex->getMessage();
            }
        }
    }
}
