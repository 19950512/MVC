<?php


namespace Sites\Admin\Controller\Index;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Sites\Admin\Admin as adm;
use Model\Sites\Sites;

class Index extends Controller
{

	protected $controller = 'Index';

	public function __construct(){

		parent::__construct();
	}

	public function index(){

		$this->_checkLogin();

		$this->viewName = 'Index';
	
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
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	private function _checkLogin(){

        if(!isset($_SESSION[SESSION_LOGIN]['id'])){
            header('location: /login');
        }
	}
}