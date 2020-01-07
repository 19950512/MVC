<?php

USE Model\Router\Router AS Router;
USE Model\Core\De AS de;

session_save_path(DIR.'/Sessions/');
session_set_cookie_params(99999999, '/', SITE_DOMINIO);

// FAZ COM QUE O MESMO COOKIE ESTEJA PRESENTE EM TODOS OS SUBDOMÍNIOS
ini_set('session.cookie_domain', '.'.SITE_DOMINIO);

session_start();

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