<?php

namespace Model\Admin;

use Model\Model;
use PDO;

use Model\Core\Core;
use Model\Core\View;
use Model\Core\De as de;

class Admin extends Model{

	function __construct(){

		parent::__construct();
	}

	function autentica($acc_id, $acc_password){

		// acc_status = 1 ATIVO
		$sql = $this->conexao->prepare('
			SELECT
				acc.acc_id,
				acc.acc_status,
				acc.acc_group,
				usu.usu_nome
			FROM conta AS acc
			LEFT JOIN usuarios AS usu ON usu.acc_codigo = acc.acc_codigo
			WHERE acc_id = :acc_id AND acc_password = :acc_password AND acc_status = 1
		');
		$sql->bindParam(':acc_id', $acc_id);
		$sql->bindParam(':acc_password', $acc_password);
		$sql->execute();
		$temp = $sql->fetch(PDO::FETCH_ASSOC);

		// Senha inválida
		if($temp === false){     
			return ['r' => 'no', 'data' => 'Senha inválida.'];
		}

		// Senha Válida

		// Cria as sessions
		$_SESSION[SESSION_LOGIN]['acc_id'] 		= $temp['acc_id'];
		$_SESSION[SESSION_LOGIN]['acc_status'] 	= $temp['acc_status'];
		$_SESSION[SESSION_LOGIN]['acc_group'] 	= $temp['acc_group'];
		$_SESSION[SESSION_LOGIN]['usu_nome'] 	= $temp['usu_nome'];

		return ['r' => 'ok', 'data' => 'Login efetuado com sucesso.'];
	}
}