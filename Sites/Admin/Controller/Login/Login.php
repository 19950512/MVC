<?php


namespace Sites\Admin\Controller\Login;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Sites\Admin\Admin\Admin as adm;
use Model\Validacao\Token;

class Login extends Controller
{
	protected $controller = 'Login';

	private $tokenLogin = 'token_pagina_login';

	public function __construct()
	{
		parent::__construct();

		// Não pode entrar nessa tela, se estiver logado.
		if($_SERVER['REQUEST_URI'] === '/login' AND isset($_SESSION[SESSION_LOGIN])){
			header('location: /');
		}
	}

	public function index(){

		$this->viewName = 'Login';

		$token = new Token;
		$token->generator($this->tokenLogin);

		$this->view->setTitle('Titulo do Login');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
			['name' => 'author', 'content' => 'GSTVara'],
			['name' => 'description', 'content' => 'Chat da Twitch é Brabo D+++']
		]);

		$mustache = array(
			'{{token}}' => $token->token,
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header, 'Login');
	}

	public function logout(){
		if(isset($_SESSION[SESSION_LOGIN])){
			unset($_SESSION[SESSION_LOGIN]);
		}

		echo json_encode(['r' => 'ok', 'data' => 'Deslogado com sucesso.']);
		exit;
	}

	public function autentica(){

		if(isset($_POST['tokenAuth'], $_POST['acc_id'], $_POST['acc_password']) AND $_POST['tokenAuth'] === $_SESSION[SESSION_TOKEN][$this->tokenLogin] AND !empty($_POST['acc_id']) AND !empty($_POST['acc_password'])){

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