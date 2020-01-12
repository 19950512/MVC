<?php


namespace Sites\Admin\Controller\Visitante;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Admin\Admin as adm;
use Model\Sites\Admin\Visitante\Render as MiniaturaRender;

class Visitante extends Controller
{

	protected $controller = 'Visitante';

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){

		$this->_checkLogin();

		$this->viewName = 'Visitante';
	
		$this->view->setTitle('Titulo do Admin');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
			['name' => 'author', 'content' => 'GSTVara'],
			['name' => 'description', 'content' => 'Chat da Twitch é Brabo D+++']
		]);
		
		$visitantes = $this->visitante->getVisitantes();
		$miniaturaVisitantes = $this->view->getView('Visitante', 'MiniaturaVisitante');
		$visitantesHtml = MiniaturaRender::miniatura($visitantes['data'], $miniaturaVisitantes);
		
		$mustache = array(
			'{{total-visitantes}}' => count($visitantes['data'] ?? []),
			'{{miniaturas-visitantes}}' => $visitantesHtml,
		);
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	private function _checkLogin(){

        if(!isset($_SESSION[SESSION_LOGIN]['acc_id'])){
            header('location: /login');
        }
	}
}