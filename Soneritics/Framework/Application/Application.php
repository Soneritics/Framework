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

use Framework\Exceptions\PageNotFoundException;
use Framework\Exceptions\PermissionDeniedException;
use Framework\Exceptions\FatalException;
use Framework\IO\Folders;
use Framework\MVC\View;
use Framework\Web\Server;
use Monolog\Logger;

/**
 * Main Application abstraction class.
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  18-9-2014
 */
abstract class Application
{
    /**
     * @var Folders
     */
    private static $folders;

    /**
     * @var Config
     */
    private static $config;

    /**
     * @var string
     */
    private static $module = 'error';

    /**
     * @var Logger
     */
    private static $log;

    /**
     * Constructor. Set-up necessary objects and connections.
     */
    final public function __construct()
    {
        self::$folders = new Folders;
        self::$log = new Logger('Framework');
    }

    /**
     * Function that gets executed before the application runs.
     * @param Routing $router
     */
    abstract protected function beforeRun(Routing $router);

    /**
     * Function that gets executed after the application runs.
     * @param Routing $router
     */
    abstract protected function afterRun(Routing $router);

    /**
     * Function that gets executed before the application runs and should
     * return true. When it returns false, a PermissionDeniedException is
     * thrown.
     * @param  Routing $router
     * @return bool Indicator wether the app may run or not.
     */
    abstract protected function canRun(Routing $router);

    /**
     * Function that gets executed before the view renders.
     * Passes the View as a variable, so it can be altered.
     * @param View $view
     */
    abstract protected function beforeRender(View $view);

    /**
     * Run the current application with a given Router and Config.
     * @param  \Framework\Application\Config  $config
     * @param  \Framework\Application\Routing $router
     * @throws PageNotFoundException
     * @throws PermissionDeniedException
     */
    final public function run(Config $config, Routing $router)
    {
        // Make the Config object available through the Application class
        self::$config = $config;

        // Only run when not in a CLI
        $server = new Server;
        if (!$server->isCLI()) {
            // Make sure the page exists
            if ($router->canRoute() === false) {
                throw new PageNotFoundException('Unable to route.');
            }

            // Check if the application allows running
            if (!$this->canRun($router)) {
                throw new PermissionDeniedException();
            }

            // Do the actual running
            $this->beforeRun($router);
            $this->dispatch($router);
            $this->afterRun($router);
        }
    }

    /**
     * Function to handle the actual running of the controller.
     * @param  \Framework\Application\Routing $router
     * @throws FatalException
     * @throws PageNotFoundException
     */
    private function dispatch(Routing $router)
    {
        // Set the module name
        self::$module = $router->getModule();

        // Create the controller
        $controllerClass = implode(
            '\\',
            [
                'Modules',
                $router->getModule(),
                'Controller',
                $router->getController()
            ]
        );

        try {
            $controller = new $controllerClass();
        } catch (Exception $ex) {
            throw new PageNotFoundException($ex->getMessage());
        }

        // Check for the action
        if (method_exists($controller, $router->getFunction() . $router->getRequestType())) {
            $action = $router->getFunction() . $router->getRequestType();
        } elseif (method_exists($controller, $router->getFunction() . 'Action')) {
            $action = $router->getFunction() . 'Action';
        } else {
            throw new PageNotFoundException('Action not found: ' .
                $router->getFunction() . $router->getRequestType());
        }

        $view = call_user_func_array(
            [$controller, $action],
            $router->getParams()
        );

        $isView = is_a($view, 'Framework\MVC\View');
        if (!is_null($view) && !$isView) {
            throw new FatalException('Unexpected controller function result: ' .
                print_r($view, true));
        } elseif ($isView) {
            $this->beforeRender($view);
            echo $view->render();
        }
    }

    /**
     * Static function to return the configuration object.
     * @return Config Configuration for the application.
     */
    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * Returns an initialized Folders object.
     * @return Folders Initialized Folders object.
     */
    public static function getFolders()
    {
        return self::$folders;
    }

    /**
     * Get the name of the module.
     * @return string
     */
    public static function getModule()
    {
        return self::$module;
    }

    /**
     * Return the logger object;
     * @return Logger
     */
    public static function log()
    {
        return self::$log;
    }
}
