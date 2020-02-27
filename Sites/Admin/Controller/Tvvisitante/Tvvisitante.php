<?php

namespace Sites\Admin\Controller\Tvvisitante;

use Model\Router\Router;
use Model\Core\Core;
use Model\Core\De as de;
use Model\Core\View as View;
use Model\Sites\Admin\Visitante\Visitante;
use Model\Sites\Admin\Tv\Tv AS PlayLists;
use Model\Sites\Admin\Tv\Render as TvRender;

class Tvvisitante {

	protected $controller = 'Tvvisitante';

	/* Object VIEW / Layout */
	public $view;

	/* Name any action */
	public $viewName;

	public $pushHistory = false;

	public $url;

	function __construct(){

		$this->view = new View();

		$this->url = new Router();

		if(isset($_POST['push']) and $_POST['push'] === 'push'){
			$this->pushHistory = true;
		}

		// A T E N Ç Ã O 
		// ISSO É TEMPORARIO, COMO NÃO HÁ DOMINIO PRECISO SETAR ISSO.
		$_SESSION[SESSION_LOGIN]['db_name'] = 'devnux';
	}

	public function index(){

		$this->_logadonaove();

		$this->viewName = 'Tvvisitante';

		$this->view->setTitle('Visitante');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$mustache = array(
			'{{controller}}' => '/'.$this->controller,
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header, 'Playlist');
	}

	public function criar(){

		$this->_logadonaove();

		$this->viewName = 'criar';

		$this->view->setTitle('Criar Login');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$mustache = array(
			'{{controller}}' => '/'.$this->controller,
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header, 'Playlist');
	}

	public function login(){

		$this->_logadonaove();

		$this->viewName = 'login';

		$this->view->setTitle('Login');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$mustache = array(
			'{{controller}}' => '/'.$this->controller,
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header, 'Playlist');
	}

	public function perfil(){

		$this->viewName = 'Perfil';

		$this->view->setTitle('Perfil');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$vis = $_SESSION[SESSION_VISITANTE];

		$mustache = array(
			'{{controller}}' => '/'.$this->controller,
			'{{vis_nome}}' => $vis['vis_nome'],
			'{{vis_email}}' => $vis['vis_email'],
			'{{vis_tel}}' => $vis['vis_tel'],
			'{{vis_cel}}' => $vis['vis_cel'],

		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function playlist(){

		if(!isset($_SESSION[SESSION_VISITANTE])){
			header('location: /tvvisitante/login');
			exit;
		}

		$this->viewName = 'playlist';

		$this->view->setTitle('Playlist');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$Vis = new Visitante();
		$visitante = $Vis->getData($_SESSION[SESSION_VISITANTE]['vis_email'] ?? '')['data'];

		// Ler o .TXT aonde fica a informação de qual playlist está tocando.
		$playlist = file_get_contents(POLLING .'/tv.txt');
		$playlist = json_decode($playlist, true);

		$playlistClass = new PlayLists;
		$Tv = $playlistClass->getPlayList($playlist['plist_codigo'])[$playlist['plist_codigo']] ?? [];

		$miniaturas = TvRender::miniatura($Tv['videos'], $this->view->getView($this->controller, 'Miniatura-video'));

		$mustache = array(
			'{{controller}}' => '/'.$this->controller,
			'{{vis_nome}}' => $visitante['vis_nome'],
			'{{plist_nome}}' => $playlist['plist_nome'],
			'{{plist_videos}}' => $miniaturas,
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function addvideo(){

		$this->viewName = 'addvideo';

		$this->view->setTitle('Adicionar Música');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		// Ler o .TXT aonde fica a informação de qual playlist está tocando.
		$playlist = file_get_contents(POLLING .'/tv.txt');
		$playlist = json_decode($playlist, true);

		$Vis = new Visitante();
		$visitante = $Vis->getData($_SESSION[SESSION_VISITANTE]['vis_email'] ?? '')['data'];

		$mustache = array(
			'{{controller}}' => '/'.$this->controller,
			'{{plist_codigo}}' => $playlist['plist_codigo'] ?? 0,
			'{{vis_codigo}}' => $visitante['vis_codigo'] ?? 0
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	function entrar(){

		if(isset($_POST['vis_email'], $_POST['vis_senha']) and !empty($_POST['vis_email']) and !empty($_POST['vis_senha'])){

			$vis_email = Core::strip_tags($_POST['vis_email'] ?? '');
			$vis_senha = Core::strip_tags($_POST['vis_senha'] ?? '');

			// Limpa a sessão
			unset($_SESSION[SESSION_VISITANTE]);

			$visitante = new Visitante();

			$resposta = $visitante->getAuthentica($vis_email, $vis_senha);

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}

	function perfilatualiza(){

		if(isset($_POST['vis_email']) and !empty($_POST['vis_email'])){

			$vis_email = Core::strip_tags($_POST['vis_email'] ?? '');
			$vis_nome = Core::strip_tags($_POST['vis_nome'] ?? '');
			$vis_tel = Core::strip_tags($_POST['vis_tel'] ?? '');
			$vis_cel = Core::strip_tags($_POST['vis_cel'] ?? '');

			$_SESSION[SESSION_VISITANTE]['vis_nome'] = $vis_nome;
			$_SESSION[SESSION_VISITANTE]['vis_email'] = $vis_email;
			$_SESSION[SESSION_VISITANTE]['vis_tel'] = $vis_tel;
			$_SESSION[SESSION_VISITANTE]['vis_cel'] = $vis_cel;

			$visitante = new Visitante();
		
			$resposta = $visitante->sync();

			echo json_encode(['r' => 'ok', 'data' => 'Pronto, dados atualizados.']);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}

	function criarvisitante(){

		if(isset($_POST['vis_email'], $_POST['vis_senha']) and !empty($_POST['vis_email']) and !empty($_POST['vis_senha'])){

			$vis_email = Core::strip_tags($_POST['vis_email'] ?? '');
			$vis_senha = Core::strip_tags($_POST['vis_senha'] ?? '');

			$visitante = new Visitante();

			$getVis = $visitante->getVisitante($vis_email);

			$_SESSION[SESSION_VISITANTE]['vis_email'] = $vis_email;
			$_SESSION[SESSION_VISITANTE]['vis_senha'] = $vis_senha;

			$resposta = $visitante->sync();

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}

	private function _logadonaove(){
		// Verifica se já está logado
		if(isset($_SESSION[SESSION_VISITANTE])){
			header('location: /'.$this->controller.'/playlist');
		}
	}

	public function render($mustache = [], $controller = '', $viewName = '', $metas = [], $layout = 'Tvvisitante'){

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

	public function logout(){
		if(isset($_SESSION[SESSION_VISITANTE])){
			unset($_SESSION[SESSION_VISITANTE]);
		}

		echo json_encode(['r' => 'ok', 'data' => 'Deslogado com sucesso.']);
		exit;
	}
}