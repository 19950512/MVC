<?php


namespace Sites\Admin\Controller\Api;

use Sites\Admin\Controller\Controller;
use Sites\Admin\Controller\Api\Login;
use Model\Core\De as de;
use Model\Router\Router;
use Model\Sites\Admin\Admin as adm;
use Model\Sites\Sites;

class Api extends Login{

	protected $controller = 'Api';

	protected $router;

	public function __construct(){
		
		$this->router = new Router;
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json;charset=utf-8');

		$this->data = json_decode(file_get_contents('php://input'), true);
	}

	public function index(){
		header('location: /');
		exit;
	}

	/* CONTROLADOR LOGIN */
	public function login(){

		if($this->router->param == 'entrar'){
			$this->login_entrar();
			exit;
		}
		if($this->router->param == 'sair'){
			$this->login_sair();
			exit;
		}
	}

	/* CONTROLADOR PESSOA */
	public function pessoa(){

		// Adicionar uma nova pessoa
		if($this->router->param == 'add'){
			$this->pessoas_add();
			exit;
		}

		// Retorna todas as pessoas
		if($this->router->param == 'get'){
			$this->pessoas_get();
			exit;
		}
	}

	public function return($r = 'ok', $data = ''){
		echo json_encode(['r' => $r, 'data' => $data]);
		exit;
	}
}