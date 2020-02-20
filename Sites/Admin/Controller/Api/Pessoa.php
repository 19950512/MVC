<?php

namespace Sites\Admin\Controller\Api;

use Model\Core\De as de;
use Model\Core\Core;
use Model\Sites\Admin\Pessoas\Pessoas;

class Pessoa {

	public function pessoas_add(){

		if(isset($this->data['pes_nome'], $this->data['pes_telefone'], $this->data['pes_sexo']) and !empty($this->data['pes_nome'])){

			$pes_nome 		= $this->data['pes_nome'] ?? '';
			$pes_telefone 	= $this->data['pes_telefone'] ?? '';
			$pes_sexo 		= $this->data['pes_sexo'] ?? 1;
			$pes_email 		= $this->data['pes_email'] ?? '';

			// Validacao
			$data['pes_nome'] 		= Core::ucwords(Core::strip_tags($pes_nome));
			$data['pes_telefone'] 	= Core::strip_tags($pes_telefone);
			$data['pes_sexo'] 		= Core::strip_tags($pes_sexo);
			$data['pes_email'] 		= Core::strip_tags($pes_email);

			// Instancia a classe Pessoas
			$pessoas = new Pessoas;

			// Insere no DB
			$resposta = $pessoas->add($data);

			$this->return($resposta['r'], $resposta['data']);
			exit;
		}

		$this->return('no', 'Ops, parece que você não informou o nome, telefone e sexo.');
		exit;
	}

	public function pessoas_get(){

		// Instancia a classe Pessoas
		$pessoas = new Pessoas;

		// Retorna as pessoas
		$resposta = $pessoas->getPessoas();
		
		$this->return($resposta['r'], $resposta['data']);
		exit;
	}
}