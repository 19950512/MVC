<?php

namespace Model\Publicacoes;

use Model\Model;
use PDO;

use Model\Core\Core;
use Model\Core\View;
use Model\Core\De as de;

class Publicacoes extends Model{

	function __construct(){

        parent::__construct();

        $this->view = new View();
	}

	private function _publicacoes(){

		$sql = $this->conexao->prepare('
			SELECT
			*
			FROM Publicacoes
			WHERE pub_status = 1
		');
		$sql->execute();
		$temp = $sql->fetchAll(PDO::FETCH_ASSOC);

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
			$fetch[$arr['pub_codigo']] = $arr;
		}

		return $fetch;
	}

	public function getPublicacoes(){

		$publicacoes = $this->_publicacoes();
		
		$html = '';
		$miniatura = $this->view->getView('Publicacoes', 'Miniatura');
		foreach ($publicacoes as $pub_codigo => $arr){

			$mustache = [
				'{{pub_titulo}}' => $arr['pub_titulo'] ?? '',
				'{{pub_subtitulo}}' => $arr['pub_subtitulo'] ?? '',
				'{{pub_texto}}' => $arr['pub_texto'] ?? '',
				'{{pub_autodata}}' => $arr['pub_autodata'] ?? '',
			];

			$html .= Core::mustache($mustache, $miniatura);

		}

		return $html;
	}
}