<?php

namespace Model\Sites\Admin\Tv;

use Model\Model;
use PDO;
use PDOException;

use Model\Core\Core;
use Model\Core\View;
use Model\Core\De as de;

class Tv extends Model{

	public $total = 0;

	function __construct(){

		parent::__construct();

		$this->total = count($this->_playLists());

		$this->view = new View();
	}

	private function _playLists($plist_codigo = ''){

		$where = '';
		if($plist_codigo !== ''){
			$where = 'WHERE plist.plist_status = 1 AND plist.plist_codigo = :plist_codigo';
		}

		try {

			$sql = $this->conexao->prepare("
				SELECT
				*
				FROM tv_playlists AS plist
				$where
				ORDER BY plist_codigo DESC, plist_nome ASC 
			");

			if($plist_codigo !== ''){
				$sql->bindParam(':plist_codigo', $plist_codigo);
			}

			$sql->execute();
			$temp = $sql->fetchAll(PDO::FETCH_ASSOC);

		}catch(PDOException $e){
			return false;
		}

		// Contém erro
		if($temp === false){     
			// Manda para o Devlogs
			new Devlogs($sql, '87321', __METHOD__);
			return false;
		}

		// Não contem erro, segue com o script
		$sql = null;

		$fetch = [];
		foreach ($temp as $key => $arr){
			$fetch[$arr['plist_codigo']] = $arr;
		}

		return $fetch;
	}

	public function getPlayLists(){

		$playlists = $this->_playLists();

		$html = '';
		$miniatura = $this->view->getView('Tv', 'Miniatura-playlist');

		foreach ($playlists as $plist_codigo => $arr){

			$mustache = [
				'{{controlador}}' 			=> 'tv',
				'{{plist_codigo}}' 			=> $arr['plist_codigo'] ?? '',
				'{{plist_nome}}' 			=> $arr['plist_nome'] ?? '',
				'{{plist_autodata_str}}' 	=> Core::datemask($arr['plist_autodata'], 'd/m/Y'),
				'{{plist_atualizacao_str}}' => Core::datemask($arr['plist_atualizacao'], 'd/m/Y'),
				'{{plist_status_str}}' 		=> ($arr['plist_status'] == '1') ? 'Ativo' : 'Inativo',
			];

			$html .= Core::mustache($mustache, $miniatura);
		}

		return ($html == '') ? '<p class="text-center">Nenhuma Play List encontrada.</p>' : $html;
	}

	public function getPlayList($plist_codigo){
		
		$playlist = $this->_playLists($plist_codigo);
		
		return $playlist;
	}

	public function salvar($data = []){

		$plist_ip = $_SERVER['REMOTE_ADDR'];

		$now = 'now()';
		$sql = $this->conexao->prepare("
			INSERT INTO tv_playlists (
				plist_nome,
				plist_status,
				plist_ip
			) VALUES (
				:plist_nome,
				:plist_status,
				:plist_ip
			)
		");
		$sql->bindParam(':plist_nome', $data['plist_nome']);
		$sql->bindParam(':plist_status', $data['plist_status']);
		$sql->bindParam(':plist_ip', $plist_ip);
		
		$sql->execute();
		$temp = $sql->fetch(PDO::FETCH_ASSOC);

		// Contém erro
		if($temp === false){     
			return ['r' => 'no', 'data' => 'Ops, não foi possível salvar a Play List, tente novamente mais tarde.'];
		}

		// Salvo com sucesso
		return ['r' => 'ok', 'data' => 'Play List salva com sucesso.'];
	}

	public function remover($plist_codigo = 0){

		$sql = $this->conexao->prepare('
			DELETE FROM tv_playlists WHERE plist_codigo = :plist_codigo
		');
		$sql->bindParam(':plist_codigo', $plist_codigo);
		$sql->execute();
		$temp = $sql->fetch(PDO::FETCH_ASSOC);

		// Contém erro
		if($temp === false){     
			return ['r' => 'no', 'data' => 'Ops, não foi possível remover a Play List, tente novamente mais tarde.'];
		}

		// Salvo com sucesso
		return ['r' => 'ok', 'data' => 'Play List removida com sucesso.'];
	}

	public function update($data = []){

		$plist_ip = $_SERVER['REMOTE_ADDR'];

		$now = 'now()';
		$sql = $this->conexao->prepare("
			UPDATE tv_playlists SET
				plist_nome = :plist_nome,
				plist_status = :plist_status,
				plist_ip = :plist_ip,
				plist_atualizacao = :plist_atualizacao
			WHERE plist_codigo = :plist_codigo
		");
		$sql->bindParam(':plist_nome', $data['plist_nome']);
		$sql->bindParam(':plist_status', $data['plist_status']);
		$sql->bindParam(':plist_ip', $plist_ip);
		$sql->bindParam(':plist_codigo', $data['plist_codigo']);
		$sql->bindParam(':plist_atualizacao', $now);
		$sql->execute();
		$temp = $sql->fetch(PDO::FETCH_ASSOC);

		// Contém erro
		if($temp === false){     
			return ['r' => 'no', 'data' => 'Ops, não foi possível salvar a Play List, tente novamente mais tarde.'];
		}

		// Salvo com sucesso
		return ['r' => 'ok', 'data' => 'Play List alterada com sucesso.'];
	}
}