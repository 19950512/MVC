<?php

namespace Sites\Admin\Controller\Api;

use Model\Sites\Admin\Login\Login as LoginAdmin;
use Model\Core\De as de;
use Sites\Admin\Controller\Api\Pessoa;

class Login extends Pessoa{

	public function login_entrar(){

		if(isset($this->data['id'], $this->data['senha']) and !empty($this->data['id']) and !empty($this->data['senha'])){

			$acc_id = $this->data['id'];
			$acc_password = $this->data['senha'];

			$resposta = $this->_auth($acc_id, $acc_password);

			$this->return($resposta['r'], $resposta['data']);
		}

		$this->return('no', 'Ops, parece que você não informou o ID, Senha ou os dois.');
	}

	public function login_sair(){
		if(isset($_SESSION[SESSION_LOGIN])){
			unset($_SESSION[SESSION_LOGIN]);
		}
		$this->return('ok', 'Desligado com sucesso.');
	}

	private function _auth($acc_id, $acc_password){

		$login = new LoginAdmin;
		$resposta = $login->autentica($acc_id, $acc_password);

		return $resposta;
	}
}