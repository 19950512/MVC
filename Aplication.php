<?php

use Model\Router\Router AS Router;
use Model\Core\De AS de;
use Model\Sessions; 

class Aplication {

	protected $router;

	function __construct(){

		$this->router = new Router();

		// site = array de informacoes do site / projeto
		$site = $this->router->sites[$_SERVER['SERVER_NAME']];

		// Inicia a session
		$sessions = new Sessions($site['dominio']);

		// Inicia o controlador
		$controller = new $this->router->namespace();

		if(!method_exists($controller, $this->router->action)){
			$this->router->set404();
			$controller = new $this->router->namespace();
		}

		$controller->{$this->router->action}();
	}
}