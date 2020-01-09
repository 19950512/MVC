<?php

namespace Model\Visitante;

use Model\Core\Core;

class Render {

	public static function miniatura($data, $mascara = ''){
		$html = '';

		if(is_array($data)){

			foreach($data as $arr){

				$mustache = [
					'{{vis_nome}}' 			=> $arr['vis_nome'] ?? '-',
					'{{vis_email}}' 		=> $arr['vis_email'] ?? '-',
					'{{vis_tel}}' 			=> $arr['vis_tel'] ?? '-',
					'{{vis_cel}}' 			=> $arr['vis_cel'] ?? '-',
					'{{vis_ip}}' 			=> $arr['vis_ip'] ?? '-',
					'{{vis_atualizacao}}' 	=> date('d/m/Y', strtotime($arr['vis_atualizacao'])).' às '.date('H:i', strtotime($arr['vis_atualizacao'])).'h',
				];

				$html .= Core::mustache($mustache, $mascara);
			}
		}

		return $html;
	}
}