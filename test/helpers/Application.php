<?php
use Framework\Application\Routing;
use Framework\MVC\View;

/**
 * 
 * 
 * @author Jordi Jolink <mail@jordijolink.nl>
 * @since  9-1-2015
 */
class Application extends Framework\Application\Application
{
    /**
     * Function after the contoller has run.
     * 
     * @param Routing $router
     */
    protected function afterRun(Routing $router)
    {
    }

    /**
     * 
     * Function before the application runs.
     * 
     * @param Routing $router
     */
    protected function beforeRun(Routing $router)
    {
    }

    /**
     * Function that gets executed before the view renders.
     * 
     * @param View $view
     */
    protected function beforeRender(View $view)
    {
    }

    /**
     * Check if the application can run.
     * 
     * @param Routing $router
     * @return boolean
     */
    protected function canRun(Routing $router)
    {
    }
}