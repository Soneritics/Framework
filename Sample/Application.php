<?php
class Application extends \Framework\Application\Application
{
    /**
     * Function that gets executed before the application runs.
     * @param \Framework\Application\Routing $router
     */
    protected function beforeRun(\Framework\Application\Routing $router)
    {
        // TODO: Implement beforeRun() method.
    }

    /**
     * Function that gets executed after the application runs.
     * @param \Framework\Application\Routing $router
     */
    protected function afterRun(\Framework\Application\Routing $router)
    {
        // TODO: Implement afterRun() method.
    }

    /**
     * Function that gets executed before the application runs and should
     * return true. When it returns false, a PermissionDeniedException is
     * thrown.
     * @param  \Framework\Application\Routing $router
     * @return bool Indicator wether the app may run or not.
     */
    protected function canRun(\Framework\Application\Routing $router)
    {
        // TODO: Implement canRun() method.
    }

    /**
     * Function that gets executed before the view renders.
     * Passes the View as a variable, so it can be altered.
     * @param \Framework\MVC\View $view
     */
    protected function beforeRender(\Framework\MVC\View $view)
    {
        // TODO: Implement beforeRender() method.
    }
}
