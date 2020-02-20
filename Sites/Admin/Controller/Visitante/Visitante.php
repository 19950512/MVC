<?php


namespace Sites\Admin\Controller\Visitante;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Sites\Admin\Visitante\Visitante as VisitanteClass;
use Model\Sites\Admin\Visitante\Render as MiniaturaRender;

class Visitante extends Controller
{

	protected $controller = 'Visitante';

	/* Object Visitante / Class Visitante */
	public $visitante;

	public function __construct()
	{
		parent::__construct();

		// Se não tem permissão para acessar
		if($this->configuracoes['conf_visitante'] === 2){
			header('location: /erro403');
		}

		
		/*
			Estrutura do array para salvar o visitante

			$_SESSION[SESSION_VISITANTE] = [
				'vis_nome' => 'Matheus Maydana',
				'vis_tel' => '(54) 3342-4545',
				'vis_cel' => '(54) 9 2000-6794',
				'vis_email' => 'email@matheus.com',
				'vis_ip' => $_SERVER['REMOTE_ADDR'],
			];
		*/

		// instancia-se a classe para registrar o visitante novo
		$this->visitante = new VisitanteClass();
	}

	public function index(){

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
}