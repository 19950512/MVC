<?php

define( 'DEV', true );

define( 'DIR', __DIR__ );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'CLASSES', DIR . DS . 'Model' );
define( 'CONTROLLER', 'Controller' );
define( 'MODEL', 'Model' );
define( 'VIEW', 'View' );
define( 'LAYOUT', 'Layout' );
define( 'EXTENSAO_VIEW', '.html' );
define( 'PATH_SITES', 'Sites' );


/* CONFIGURAÇÕES DO SITE */
define( 'SESSION_LOGIN', 'LOGIN' );
define( 'SESSION_CONFIGURACOES', 'CONFIGURACOES' );
define( 'SESSION_VISITANTE', 'VISITANTE' );
define( 'SESSION_TOKEN', 'TOKEN' );

/* AUTHOR - DEVELOPER */
define ( 'AUTHOR', 'DevNux' );


/* DB - arquivo Db.php 
	define ( 'DB_HOST', '127.0.0.1' );
	define ( 'DB_NAME', 'database_name' );
	define ( 'DB_USER', 'nome_user' );
	define ( 'DB_PASSWORD', 'senha_do_db' );
	define ( 'DB_PORT', '5432' );
*/
require_once 'Db.php';

define( 'AUTOLOAD_CLASSES', serialize(array(CLASSES, CONTROLLER, VIEW, MODEL)));