<?php

namespace Sites\Admin\Controller\Publicacao;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Validacao\Token;
use Model\Sites\Admin\Publicacoes\Publicacoes;

class Publicacao extends Controller {

	protected $controller = 'Publicacao';

	private $tokenLogin = 'token_pagina_publicacao';

	private $pub;

	public function __construct()
	{
		parent::__construct();
		
		// Se não tem permissão para acessar as publicações		
		if($this->configuracoes['conf_publicacao'] === 2){
			header('location: /erro403');
		}

		$this->pub = new Publicacoes;
	}

	public function index(){

		$this->viewName = 'Publicacao';

		$publicacoes = $this->pub->getPublicacoes();

		$this->view->setTitle('Publicação');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);


		$mustache = array(
			'{{miniaturas-publicacoes}}' => $publicacoes,
			'{{total-publicacoes}}' => $this->pub->total,
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function ver(){

		$this->viewName = 'Ver';

		$publicacao = $this->pub->getPublicacao($this->url->param)[$this->url->param] ?? [];

		if(!isset($publicacao['pub_titulo'])){
			header('location: /pagina-nao-encontrada');
			exit;
		}

		$this->view->setTitle('Publicação');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);

		$mustache = array(
			'{{controlador}}' => $this->controller,
			'{{publicacao-codigo}}' => $publicacao['pub_codigo'] ?? '',
			'{{publicacao-titulo}}' => $publicacao['pub_titulo'] ?? '',
			'{{publicacao-subtitulo}}' => $publicacao['pub_subtitulo'] ?? '',
			'{{publicacao-texto}}' => $publicacao['pub_texto'] ?? '',
			'{{publicacao-status}}' => $publicacao['pub_status'] ?? '',
		);
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function nova($pub_codigo = ''){

		$this->viewName = 'Nova';

		$publicacao = $this->pub->getPublicacao($this->url->param)[$pub_codigo] ?? [];

		if($pub_codigo !== ''){
			if(!isset($publicacao['pub_titulo'])){
				header('location: /pagina-nao-encontrada');
				exit;
			}
		}

		$token = new Token;
		$token->generator($this->tokenLogin);
	
		$this->view->setTitle('Nova Publicação');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
		]);
	
		$mustache = array(
			'{{token}}' => $token->token,
			'{{acao}}' => ($pub_codigo !== '') ? 1 : 2,
			'{{mostrarnao}}' => ($publicacao['pub_comentar'] ?? '1' == 2) ? 'selected="selected"': '',
			'{{mostrarsim}}' => ($publicacao['pub_comentar'] ?? '1' == 1) ? 'selected="selected"': '',
			'{{pub_titulo}}' => $publicacao['pub_titulo'] ?? '',
			'{{pub_subtitulo}}' => $publicacao['pub_subtitulo'] ?? '',
			'{{pub_texto}}' => $publicacao['pub_texto'] ?? '',
			'{{pub_status}}' => $publicacao['pub_status'] ?? '1',
			'{{pub_codigo}}' => $publicacao['pub_codigo'] ?? '',
			'{{controlador}}' => $this->url->controller,
		);
		
		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function editar(){
		$this->nova($this->url->param);
		exit;
	}

	function save(){

		if(isset($_POST['tokenAuth']) AND $_POST['tokenAuth'] === $_SESSION[SESSION_TOKEN][$this->tokenLogin]){

			$pub_titulo 		= $_POST['titulo'] ?? '';
			$pub_subtitulo 		= $_POST['descricao'] ?? '';
			$pub_texto 			= $_POST['texto'] ?? '';
			$pub_status	 		= $_POST['status'] ?? '';
			$pub_codigo			= $_POST['pub_codigo'] ?? '';
			$pub_comentar 		= $_POST['comentar'] ?? '1'; // Se permite comentar na publicação, 1 sim 2 não

			$data = [
				'pub_titulo' => $pub_titulo, 
				'pub_subtitulo' => $pub_subtitulo, 
				'pub_texto' => $pub_texto, 
				'pub_comentar' => $pub_comentar,
				'pub_status' => $pub_status,
				'pub_codigo' => $pub_codigo,
			];

			$publicar = new Publicacoes();

			// Editar
			if($_POST['acao'] == 1){
				$resposta = $publicar->update($data);
			}else{
			// Salvar
				$resposta = $publicar->salvar($data);
			}

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}
}