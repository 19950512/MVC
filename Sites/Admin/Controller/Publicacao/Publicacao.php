<?php

namespace Sites\Admin\Controller\Publicacao;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Sites\Admin\Admin as adm;

class Publicacao extends Controller
{

	protected $controller = 'Publicacao';

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){

		$this->viewName = 'Publicacao';
	
		$this->view->setTitle('Publicação');
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
}