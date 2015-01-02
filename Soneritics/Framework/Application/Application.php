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

/**
 * Main Application abstraction class.
 * 
 * @author Jordi Jolink
 * @since 18-9-2014
 */
abstract class Application
{
	private static $folders;
    private static $config;

	/**
	 * Constructor. Set-up necessary objects and connections.
	 */
	public final function __construct()
	{
		self::$folders = new Folders();
	}

    /**
     * Function that gets executed before the application runs.
     * 
     * @param Routing $router
     */
    protected abstract function beforeRun(Routing $router);

    /**
     * Function that gets executed after the application runs.
     * 
     * @param Routing $router
     */
    protected abstract function afterRun(Routing $router);

    /**
     * Function that gets executed before the application runs and should
     * return true. When it returns false, a PermissionDeniedException is
     * thrown.
     * 
     * @param Routing $router
     * @return bool Indicator wether the app may run or not.
     */
    protected abstract function canRun(Routing $router);

    /**
     * Function that gets executed before the view renders.
     * Passes the View as a variable, so it can be altered.
     * 
     * @param View $view
     */
    protected abstract function beforeRender(View $view);

    /**
     * Run the current application with a given Router and Config.
     * 
     * @param \Framework\Application\Config $config
     * @param \Framework\Application\Routing $router
     * @throws PageNotFoundException
     * @throws PermissionDeniedException
     */
    public final function run(Config $config, Routing $router)
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
     * 
     * @param \Framework\Application\Routing $router
     * @throws PageNotFoundException
     */
    private function dispatch(Routing $router)
    {
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
            throw new PageNotFoundException(
                'Action not found: ' .
                $router->getFunction() . $router->getRequestType()
            );
        }

        $view = call_user_func_array(
            [$controller, $action],
            $router->getParams()
        );

        $isView = is_a($view, 'Framework\MVC\View');
        if (!is_null($view) && !$isView) {
            throw new FatalException(
                'Unexpected controller function result: ' .
                print_r($view, true)
            );
        } elseif ($isView) {
            $this->beforeRender($view);
            echo $view->render(
                new \Framework\Renderer\HtmlRenderer($router->getModule()) // @todo: fixme
            );
        }
    }

    /**
     * Static function to return the configuration object.
     * 
     * @return Config Configuration for the application.
     */
    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * Returns an initialized Folders object.
     * 
     * @return Folders Initialized Folders object.
     */
    public static function getFolders()
    {
        return self::$folders;
    }

    /**
     * Use the framework's logger to log a message.
     */
    public static function log()
    {
        call_user_func_array(
            ['Framework\Logging\Log', 'write'],
            func_get_args()
        );
    }
}