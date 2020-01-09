<?php

define( 'DEV', true );

define( 'DIR', __DIR__ );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'CLASSES', DIR . DS . 'Model' );
define( 'CONTROLLER', DIR . DS . 'Controller' );
define( 'MODEL', DIR . DS . 'Model' );
define( 'VIEW', DIR . DS . 'View' );
define( 'LAYOUT', DIR . DS . 'View/Layout' );
define( 'EXTENSAO_VIEW', '.html' );


/* CONFIGURAÇÕES DO SITE */
define( 'SITE_NOME', 'DevNux - Family' );
define( 'SITE_DOMINIO', 'mvc2.local' );
define( 'SESSION_LOGIN', 'LOGIN' );
define( 'SESSION_VISITANTE', 'VISITANTE' );

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