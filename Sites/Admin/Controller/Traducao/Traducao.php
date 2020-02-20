<?php


namespace Sites\Admin\Controller\Traducao;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Sites\Admin\Traducao\Traducao as TraducaoClass;
use Model\Sites\Admin\Traducao\Render as MiniaturaRender;

class Traducao extends Controller {

	protected $controller = 'Traducao';

	/* Object Traducao / Class Traducao */
	public $traducao;

	public function __construct() {
		parent::__construct();

		$this->traducao = new TraducaoClass();
	}

	public function index(){

		$this->viewName = 'Traducao';
	
		$this->view->setTitle('Traduções');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$traducoes = $this->traducao->getTraducoes();
		$miniaturaTraducoes = $this->view->getView('Traducao', 'MiniaturaTraducao');
		$traducoesHtml = MiniaturaRender::miniatura($traducoes, $miniaturaTraducoes);
		
		$mustache = array(
			'{{miniaturas-traducoes}}' => $traducoesHtml,
		);
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function salvar(){

		if(isset($_POST['trad_codigo'])){

			$resposta = ['r' => 'ok', 'data' => 'Tudo certo até aqui..'];

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}
}