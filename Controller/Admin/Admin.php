<?php


namespace Controller\Admin;

use Controller\Controller;
use Model\Core\De as de;
use Model\Admin\Admin as adm;

class Admin extends Controller
{

	protected $controller = 'Admin';

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){

        if(!isset($_SESSION[SESSION_LOGIN]['acc_id'])){
            header('location: /admin/login');
        }

		$this->viewName = 'Admin';
	
		$this->view->setTitle('Titulo do Admin');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
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

		if(isset($_POST['acc_id'], $_POST['acc_password']) AND !empty($_POST['acc_id']) AND !empty($_POST['acc_password'])){

			$acc_id 		= $_POST['acc_id'] ?? '';
			$acc_password 	= $_POST['acc_password'] ?? '';

			$admin = new adm;
			$resposta = $admin->autentica($acc_id, $acc_password);

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}

}