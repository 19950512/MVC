<?php

USE Model\Router\Router AS Router;
USE Model\Core\De AS de;

class Aplication
{
    protected $router;

	function __construct()
    {
        $this->router = new Router();
        $controller = new $this->router->namespace();

        if(!method_exists($controller, $this->router->action)){
            $this->router->set404();
            $controller = new $this->router->namespace();
        }

        $controller->{$this->router->action}();
    }
}