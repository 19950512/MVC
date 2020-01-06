<?php


namespace Controller\Admin;

use Controller\Controller;
use Model\Core\De as de;

class Admin extends Controller
{

	protected $controller = 'Admin';

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){

        if(!isset($_SESSION['LOGIN']['id'])){
            header('location: /admin/login');
        }

		$this->viewName = 'Admin';
	
		$this->view->setTitle('Titulo do Admin');
		$this->view->setHeader([
			['name' => 'robots', 'content' => '100 ROBOTS'],
			['name' => 'author', 'content' => 'GSTVara'],
			['name' => 'description', 'content' => 'Chat da Twitch é Brabo D+++']
		]);
	
		$mustache = array(
			'{{pushResposta}}' => ' OK, Maestro'
		);
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header, 'Admin');
	}

	public function enviarEmail(){

	}

	public function login(){
		$this->viewName = 'Login';
	
		$this->view->setTitle('Login');
	
		$mustache = array();
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header, 'Admin');
	}


	public function autentica(){

		if(isset($_POST['id'], $_POST['senha']) AND !empty($_POST['id']) AND !empty($_POST['senha'])){

			$id 	= $_POST['id'] ?? '';
			$senha 	= $_POST['senha'] ?? '';

			$_SESSION['LOGIN']['id'] = $id;

			echo json_encode(['r' => 'ok', 'data' => 'Dados certinhos..']);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}

}