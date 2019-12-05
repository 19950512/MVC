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
define( 'SITE_DOMINIO', 'mvc.local' );


/* AUTHOR - DEVELOPER */
define ( 'AUTHOR', 'DevNux' );


/* DB */
define ( 'DB_HOST', '127.0.0.1' );
define ( 'DB_NAME', 'db_name_nao_existe_ainda' );
define ( 'DB_USER', 'user_nao_existe_ainda' );
define ( 'DB_PASSWORD', 'dgstVara' );
define ( 'DB_PORT', '5432' );

define( 'AUTOLOAD_CLASSES', serialize(array(CLASSES, CONTROLLER, VIEW, MODEL)));