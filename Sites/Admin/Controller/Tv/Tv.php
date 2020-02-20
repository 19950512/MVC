<?php

namespace Sites\Admin\Controller\Tv;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Core\Core;
use Model\Validacao\Token;
use Model\Sites\Admin\Tv\Tv AS PlayLists;

class Tv extends Controller {

	protected $controller = 'Tv';

	private $tokenLogin = 'token_pagina_Tv';

	private $tv;

	public function __construct()
	{
		parent::__construct();

		// Se não tem permissão para acessar as publicações
		if($this->configuracoes['conf_tv'] === 2){
			header('location: /erro403');
		}

		$this->tv = new PlayLists;
	}

	public function index(){

		$this->viewName = 'Tv';

		$playlists = $this->tv->getPlayLists();

		$this->view->setTitle('Publicação');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$mustache = array(
			'{{miniaturas-playlists}}' => $playlists,
			'{{total-playlists}}' => $this->tv->total,
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function ver(){

		$this->viewName = 'Ver';

		$Tv = $this->tv->getPlayList($this->url->param)[$this->url->param] ?? [];

		if(!isset($Tv['plist_nome'])){
			header('location: /pagina-nao-encontrada');
			exit;
		}

		$this->view->setTitle('Playlist - '.$Tv['plist_nome']);
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$mustache = array(
			'{{controlador}}' => $this->controller,
			'{{plist_codigo}}' => $Tv['plist_codigo'],
			'{{plist_nome}}' => $Tv['plist_nome'],
		);
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function nova($plist_codigo = ''){

		$this->viewName = 'Nova';

		$Tv = $this->tv->getPlayList($this->url->param)[$plist_codigo] ?? [];

		if($plist_codigo !== ''){
			if(!isset($Tv['plist_nome'])){
				header('location: /pagina-nao-encontrada');
				exit;
			}
		}

		$token = new Token;
		$token->generator($this->tokenLogin);
		
		$tituloPagina = 'Nova Play List';

		if(isset($Tv['plist_codigo'])){
			$tituloPagina = 'Editando Play List - '.$Tv['plist_nome'];
		}

		$this->view->setTitle($tituloPagina);
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);
		
		// Options Render.
		$status = [
			1 => 'Sim',
			2 => 'Não',
		];

		$statusOptions = '';
		foreach ($status as $plist_status => $label){

			$selected = '';
			if($plist_status == ($Tv['plist_status'] ?? 1)){
				$selected = 'selected="selected"';
			}

			$statusOptions .= '<option '.$selected.' value="'.$plist_status.'">'.$label.'</option>';
		}

		$mustache = array(
			'{{token}}' => $token->token,
			'{{acao}}' => ($plist_codigo !== '') ? 1 : 2,
			'{{statusOptions}}' => $statusOptions,
			'{{plist_nome}}' => $Tv['plist_nome'] ?? '',
			'{{plist_codigo}}' => $Tv['plist_codigo'] ?? '',
			'{{controlador}}' => $this->url->controller,
			'{{titulo_pagina}}' => $tituloPagina
		);
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function editar(){
		$this->nova($this->url->param);
		exit;
	}

	function remove(){

		if(isset($_POST['plist_codigo']) and is_numeric($_POST['plist_codigo'])){

			$plist_codigo = $_POST['plist_codigo'] ?? '';
			
			$resposta = $this->tv->remover($plist_codigo);

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}

	function save(){

		if(isset($_POST['tokenAuth']) AND $_POST['tokenAuth'] === $_SESSION[SESSION_TOKEN][$this->tokenLogin]){

			$plist_nome 		= Core::strip_tags($_POST['plist_nome'] ?? '');
			$plist_status 		= (int) Core::strip_tags($_POST['plist_status'] ?? '');
			$plist_codigo 		= Core::strip_tags($_POST['plist_codigo'] ?? '');
			
			$data = [
				'plist_nome' => $plist_nome, 
				'plist_status' => $plist_status, 
				'plist_codigo' => $plist_codigo,
			];

			// Editar
			if($_POST['acao'] == 1){
				$resposta = $this->tv->update($data);
			}else{
			// Salvar
				$resposta = $this->tv->salvar($data);
			}

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}


	function addmusica(){

		if(isset($_POST['tv_url'], $_POST['plist_codigo']) AND is_numeric($_POST['plist_codigo']) AND is_string($_POST['tv_url'])){

			$tv_url 		= Core::strip_tags($_POST['tv_url'] ?? '');
			$plist_codigo 	= Core::strip_tags($_POST['plist_codigo'] ?? '');
			
			$data = [
				'tv_url' => $tv_url,
				'plist_codigo' => $plist_codigo,
			];

			// Editar
			if($_POST['acao'] == 1){
				//$resposta = $this->tv->update($data);
			}else{
			// Salvar
				$resposta = $this->tv->addmusica($data);
			}

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}
}