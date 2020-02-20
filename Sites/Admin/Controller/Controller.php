<?php

namespace Sites\Admin\Controller;

use Model\Core\View as View;
use Model\Core\De as de;
use Model\Sites\Admin\Visitante\Visitante;
use Model\Sites\Admin\Login\Login;
use Model\Router\Router;

class Controller {

	/* Object VIEW / Layout */
	public $view;

	/* Name any action */
	public $viewName;
	
	public $pushHistory = false;

	public $url;

	public $configuracoes;

	public function __construct(){

		if(isset($_POST['push']) and $_POST['push'] === 'push'){
			$this->pushHistory = true;
		}

		// Veririficar se o usuário está logado.
		if(!isset($_SESSION[SESSION_LOGIN]) AND $_SERVER['REQUEST_URI'] !== '/login' AND $_SERVER['REDIRECT_URL'] !== '/login/autentica'){
			header('location: /login');
		}

		// Check authentic - Valida ID e Senha SEMPRE
		if(isset($_SESSION[SESSION_LOGIN]['id']) AND !empty($_SESSION[SESSION_LOGIN]['id'])){

			// Pega ID e SENHA da sessão
			$id = $_SESSION[SESSION_LOGIN]['id'];
			$senha = $_SESSION[SESSION_LOGIN]['senha'];

			$login = new Login();
			$resposta = $login->autentica($id, $senha);

			// A senha está errada
			if($resposta['r'] == 'no'){
				/*
					Se a senha foi alterada, então deve derrubar todas as sessões desse cliente/empresa.

					Pois se não derrubar os outros usuários ainda manteram o login e isso é péssimo.

					Oque precisa ser feito?
					* Armazenar em banco o nome da sessão
					* Com o nome da sessão, procurar no arquivo de Sessions 
					* Excluir o arquivo com php.
					
					Só assim o recurso será implementado corretamente.
				*/
				header('location: /login/logout');
			}
		}

		$this->configuracoes = $_SESSION[SESSION_CONFIGURACOES] ?? [];

		$this->view = new View();

		$this->url = new Router();
	}

	
	public function render($mustache = [], $controller = '', $viewName = '', $metas = [], $layout = 'Layout'){

		/* Se for por F5 */
		if($this->pushHistory === false){
		
			echo $this->view->mustache($mustache, $this->view->getView($controller, $viewName), $layout);
			exit;
			
		}else{

			/* Se for por pushHistory */
			$result['html'] = $this->view->pushHistory($mustache, $this->view->getView($controller, $viewName), $layout);
			$result['metas'] = [
				'title' => $this->view->title,
				/*'description' => array_filter($this->view->header, fn($x) => ($x['name'] === 'description') ? $x['content'] : '')[1]['content']*/
			];

			echo json_encode($result);
			exit;
		}
	}
}