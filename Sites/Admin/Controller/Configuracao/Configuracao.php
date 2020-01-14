<?php

namespace Sites\Admin\Controller\Configuracao;

use Sites\Admin\Controller\Controller;
use Model\Core\De as de;
use Model\Sites\Admin\Admin as adm;
use Model\Sites\Admin\Configuracao\Configuracao as Config;
use Model\Validacao\Token;

class Configuracao extends Controller {

	protected $controller = 'Configuracao';
	
	private $tokenLogin = 'token_pagina_configuracao';

	private $empresa;

	public function __construct(){
		parent::__construct();
	
		$this->empresa = new Config;
	}

	public function index(){

		$this->viewName = 'Configuracao';

		$this->view->setTitle('Configuração');
		$this->view->setHeader([
			['name' => 'robots', 'content' => 'noindex, nofollow'],
			['name' => 'author', 'content' => 'GSTVara'],
			['name' => 'description', 'content' => 'Chat da Twitch é Brabo D+++']
		]);

		// Configurações da empresa, todos os campos.
		$empresa = $this->empresa->getConfiguracao();
	
		$token = new Token;
		$token->generator($this->tokenLogin);

		$mustache = array(
			'{{emp_cep}}' 			=> $empresa['emp_cep'] ?? '',
			'{{emp_nome}}' 			=> $empresa['emp_nome'] ?? '',
			'{{emp_cnpj}}' 			=> $empresa['emp_cnpj'] ?? '',
			'{{tokenAuth}}' 		=> $token->token,
			'{{emp_email}}' 		=> $empresa['emp_email'] ?? '',
			'{{emp_codigo}}'		=> $empresa['emp_codigo'] ?? '',
			'{{emp_cidade}}' 		=> $empresa['emp_cidade'] ?? '',
			'{{emp_bairro}}' 		=> $empresa['emp_bairro'] ?? '',
			'{{emp_twitter}}' 		=> $empresa['emp_twitter'] ?? '',
			'{{emp_celular}}' 		=> $empresa['emp_celular'] ?? '',
			'{{emp_telefone}}' 		=> $empresa['emp_telefone'] ?? '',
			'{{emp_facebook}}' 		=> $empresa['emp_facebook'] ?? '',
			'{{emp_linkedin}}' 		=> $empresa['emp_linkedin'] ?? '',
			'{{emp_whatsapp}}' 		=> $empresa['emp_whatsapp'] ?? '',
			'{{emp_endereco}}' 		=> $empresa['emp_endereco'] ?? '',
			'{{emp_instagram}}' 	=> $empresa['emp_instagram'] ?? '',
		);

		// Render View
		$this->render($mustache, $this->controller, $this->viewName, $this->view->header);
	}

	public function save(){

		if(isset($_POST['tokenAuth']) AND $_POST['tokenAuth'] === $_SESSION[SESSION_TOKEN][$this->tokenLogin]){

			// Valida os campos
			$valida = $this->empresa->valida($_POST);

			// Caso não passe na validação
			if($valida['r'] == 'no'){
				echo json_encode($valida);
				exit;
			}

			// Tenta atualizar os dados no DB
			$resposta = $this->empresa->update($_POST);

			// Exibe a resposta
			echo json_encode($resposta);
			exit;
		}
		
		echo json_encode(['r' => 'no', 'data' => 'Ops, tente novamente mais tarde.']);
		exit;
	}
}