<?php


namespace Model\Sites;
USE Model\Core\De AS de;

class Sites {

	public $sites = [
		'admin.local' => [
			'dominio' => 'admin.local',
			'nome' => 'Painel Administrativo - DevNux',
			'namespace' => 'Admin',
			'status' => 1, // 1 Ativo, 2 Inativo
			'path' => 'Sites',
			'statics' => '//statics.admin.local'
		],
		'devnux.local' => [
			'dominio' => 'devnux.local',
			'nome' => 'Devvv Nuxx',
			'namespace' => 'DevNux',
			'status' => 1, // 1 Ativo, 2 Inativo
			'path' => 'Sites',
			'statics' => '//statics.devnux.local'
		],
		'cholamais.local' => [
			'dominio' => 'cholamais.local',
			'nome' => 'Chola Mais',
			'namespace' => 'Cholamais',
			'status' => 1, // 1 Ativo, 2 Inativo
			'path' => 'Sites',
			'statics' => '//statics.cholamais.local'
		],
	];
}