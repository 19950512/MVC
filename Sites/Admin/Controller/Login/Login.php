<?php


namespace Sites\Admin\Controller\Login;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Sites\Admin\Admin as adm;

class Login extends Controller
{
	protected $controller = 'Login';

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){

		$this->viewName = 'Login';
	
		$this->view->setTitle('Titulo do Login');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
			['name' => 'author', 'content' => 'GSTVara'],
			['name' => 'description', 'content' => 'Chat da Twitch é Brabo D+++']
		]);
	
		$mustache = array(
			'{{pushResposta}}' => ' OK, Maestro'
		);
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
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