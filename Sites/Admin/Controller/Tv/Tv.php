<?php

namespace Sites\Admin\Controller\Tv;

use Sites\Admin\Controller\Controller;
use Model\Plugins\Youtube;
use Model\Core\De as de;
use Model\Core\Core;
use Model\Validacao\Token;
use Model\Sites\Admin\Tv\Tv AS PlayLists;
use Model\Sites\Admin\Tv\Render;

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

		$musicas_playlist = Render::miniatura($Tv['videos'], $this->view->getView($this->controller, 'Miniatura-video'));

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
			'{{qnt-musicas}}' => count($Tv['videos']),
			'{{musicas_playlist}}' => $musicas_playlist,
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
			$vis_codigo 	= Core::strip_tags($_POST['vis_codigo'] ?? 0);
				
			// Validação

			$youtube = new Youtube();

			$youtube->add($tv_url);

			$videos = $youtube->videos;

			if(is_array($videos) and count($videos) == 0){

				echo json_encode(['r' => 'no', 'data' => 'Bro, não encontrei essa música no youtube. verifica o link, deve estar errado.']);
				exit;
			}

			// Vídeo encontrado, agora vamos validar a duração, etc..
			$id = '';
			foreach($videos as $id_video => $arr){

				$duracao = str_split($arr['duracao']);
				$live = array_flip($duracao);
				$id = $id_video;

				// Se não houver M, deve ser LIVE
				if(!isset($live['M'])){
					echo json_encode(['r' => 'no', 'data' => 'Não aceitamos LIVE como vídeo, procure outro.']);
					exit;
				}

				foreach ($duracao as $valor => $indice){

					if($indice == 'H'){
						echo json_encode(['r' => 'no', 'data' => 'Bro, seu vídeo tem mais de 1 Hora, escolha um com +/- 5 minutos.']);
						exit;
					}

					// Se a duração do vídeo houver M, quer dizer que tem minutos SE FOR maior que 6min não passa
					if($indice == 'M' AND $duracao[$valor - 1] >= 6){
						echo json_encode(['r' => 'no', 'data' => 'Irmão, seu vídeo tem mais de 6 Minutos, escolha um vídeo com uma duração menor que 6 minutos.']);
						exit;
					}
				}
			}

			$data = [
				'tv_url' 			=> $videos[$id]['url'] ?? '',
				'tv_miniatura' 		=> $videos[$id]['miniatura'] ?? '',
				'tv_visualizacoes' 	=> $videos[$id]['visualizacoes'] ?? '',
				'tv_duracao' 		=> $videos[$id]['duracao'] ?? '',
				'tv_publicado' 		=> $videos[$id]['publicado'] ?? '',
				'tv_like' 			=> $videos[$id]['like'] ?? '',
				'tv_dislike' 		=> $videos[$id]['dislike'] ?? '',
				'tv_favorito' 		=> $videos[$id]['favorito'] ?? '',
				'tv_comentarios' 	=> $videos[$id]['comentarios'] ?? '',
				'tv_descricao' 		=> $videos[$id]['descricao'] ?? '',
				'tv_titulo' 		=> $videos[$id]['titulo'] ?? '',
				'plist_codigo' 		=> $plist_codigo,
				'vis_codigo' 		=> $vis_codigo,
			];

			// Salva
			$resposta = $this->tv->addmusica($data);

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}
	
	function removermusica(){

		if(isset($_POST['tv_codigo']) AND is_numeric($_POST['tv_codigo'])){

			$tv_codigo = Core::strip_tags($_POST['tv_codigo'] ?? '');

			$resposta = $this->tv->removermusica($tv_codigo);

			echo json_encode($resposta);
			exit;
		}

		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}
}