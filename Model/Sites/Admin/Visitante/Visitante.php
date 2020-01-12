<?php

namespace Model\Sites\Admin\Visitante;

use Model\Model;
use PDO;

class Visitante extends Model{

	/**
	**	@see LÓGICA para adicionar um novo visitante.
	** - Verificar se na session['vis_nome'] || session['vis_email'] contém algum nome, quando houver é porque o usuário preencheu algum formulário e se identificou
	** - Consultar no DB se existe alguem com esse nome ou email.
	**		- se tiver, atualizar os dados
	**		- se não tiver, cadastrar um novo visitante
	**/

	function __construct(){
		parent::__construct();

		// Suposto Novo visitante
		if(isset($_SESSION[SESSION_VISITANTE]['vis_email']) and !empty($_SESSION[SESSION_VISITANTE]['vis_email'])){

			// Sincronizar as parada
			$this->sync();
		}
	}

	public function sync(){
		// Verificar se existe alguem já com esse email
		$visitante = $this->_getVisitante($_SESSION[SESSION_VISITANTE]['vis_email']);

		// Adiciona novo visitante
		if($visitante['r'] == 'no'){
		
			$this->_putVisitante();
		
		// Atualiza o visitante
		}elseif($visitante['r'] == 'ok'){

			$this->_updateVisitante($_SESSION[SESSION_VISITANTE]['vis_email']);
		}
	}

	// GETTERS
	public function getVisitante($vis_email){
		return $this->_getVisitante($vis_email);
	}
	public function getVisitantes(){
		return $this->_getVisitantes();
	}

	// Insere um novo visitante
	// return 'no' => 'Não cadastrado'
	// return 'ok' => 'Cadastrado com sucesso'
	private function _putVisitante(){

		$sql = $this->conexao->prepare('
			INSERT INTO visitante (
				vis_nome,
				vis_tel,
				vis_cel,
				vis_email,
				vis_ip
			) VALUES (
				:vis_nome,
				:vis_tel,
				:vis_cel,
				:vis_email,
				:vis_ip
			)
		');
		$sql->bindParam(':vis_nome', $_SESSION[SESSION_VISITANTE]['vis_nome']);
		$sql->bindParam(':vis_tel', $_SESSION[SESSION_VISITANTE]['vis_tel']);
		$sql->bindParam(':vis_cel', $_SESSION[SESSION_VISITANTE]['vis_cel']);
		$sql->bindParam(':vis_email', $_SESSION[SESSION_VISITANTE]['vis_email']);
		$sql->bindParam(':vis_ip', $_SESSION[SESSION_VISITANTE]['vis_ip']);
		$sql->execute();
		$temp = $sql->fetch(PDO::FETCH_ASSOC);

		// Visitante não cadastrado
		if($temp === false){     
			return ['r' => 'no', 'data' => 'Visitante não cadastrado.'];
		}

		return ['r' => 'ok', 'data' => 'Visitante cadastrado com sucesso.'];
	}

	// Busca informações de um visitante apartir de um e-mail
	// return 'no' => 'Não encontrado'
	// return 'ok' => 'Encontrado'
	private function _getVisitante($vis_email){

		// vis_ativo = 1 ATIVO
		$sql = $this->conexao->prepare('
			SELECT
				vis.vis_codigo,
				vis.vis_nome,
				vis.vis_email,
				vis.vis_cel,
				vis.vis_tel,
				vis.vis_ip,
				vis.vis_status,
				vis.vis_atualizacao,
				vis.vis_autodata
			FROM visitante AS vis
			WHERE vis_email = :vis_email
		');
		$sql->bindParam(':vis_email', $vis_email);
		$sql->execute();
		$temp = $sql->fetch(PDO::FETCH_ASSOC);

		// Visitante não encontrado (então é um novo visitante)
		if($temp === false){     
			return ['r' => 'no', 'data' => 'Visitante não encontrado (então é um novo visitante)'];
		}

		return ['r' => 'ok', 'data' => 'Visitante encontrato, chama-se '. $temp['vis_nome']];
	}

	// Busca informações de todos os visitantes
	// return 'no' => 'Não encontrado'
	// return 'ok' => 'Encontrado'
	private function _getVisitantes(){

		// vis_ativo = 1 ATIVO
		$sql = $this->conexao->prepare('
			SELECT
				vis.vis_codigo,
				vis.vis_nome,
				vis.vis_email,
				vis.vis_cel,
				vis.vis_tel,
				vis.vis_ip,
				vis.vis_status,
				vis.vis_atualizacao,
				vis.vis_autodata
			FROM visitante AS vis
			ORDER BY vis.vis_codigo DESC, vis.vis_nome ASC
		');
		$sql->execute();
		$temp = $sql->fetchAll(PDO::FETCH_ASSOC);

		// Visitante não encontrado (então é um novo visitante)
		if($temp === false){     
			return ['r' => 'no', 'data' => 'Visitante não encontrado (então é um novo visitante)'];
		}

		return ['r' => 'ok', 'data' => $temp];
	}

	// Atualizar visitante (ultima visita)
	// return 'no' => 'Não atualizou'
	// return 'ok' => 'Atualizou com sucesso'
	private function _updateVisitante($vis_email){

		$sql = $this->conexao->prepare("
			UPDATE visitante SET 
				vis_atualizacao = 'now()',
				vis_ip = :vis_ip
			WHERE vis_email = :vis_email 
		");
		$sql->bindParam(':vis_email', $_SESSION[SESSION_VISITANTE]['vis_email']);
		$sql->bindParam(':vis_ip', $_SESSION[SESSION_VISITANTE]['vis_ip']);
		$sql->execute();
		$temp = $sql->fetch(PDO::FETCH_ASSOC);

		// Visitante não cadastrado
		if($temp === false){     
			return ['r' => 'no', 'data' => 'Visitante não cadastrado.'];
		}

		return ['r' => 'ok', 'data' => 'Visitante cadastrado com sucesso.'];
	}
}