<?php

define( 'DEV', true );

define( 'DIR', __DIR__ );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'CLASSES', DIR . DS . 'Model' );
define( 'CONTROLLER', DIR . DS . 'Controller' );
define( 'MODEL', DIR . DS . 'Model' );
define( 'VIEW', DIR . DS . 'View' );
define( 'LAYOUT', DIR . DS . 'View/Layout' );
define( 'EXTENSAO_VIEW', '.html');

define( 'AUTOLOAD_CLASSES', serialize(array(CLASSES, CONTROLLER, VIEW, MODEL)));