<?php


namespace Controller\Contato;

use Controller\Controller;
use Model\Core\De as de;

class Contato extends Controller
{

    protected $controller = 'Contato';

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
	    $this->viewName = 'Contato';
	
	    $this->view->setTitle('Titulo do Contato');
	    $this->view->setHeader([
		    ['name' => 'robots', 'content' => '100 ROBOTS'],
		    ['name' => 'author', 'content' => 'GSTVara'],
		    ['name' => 'description', 'content' => 'Chat da Twitch é Brabo D+++']
	    ]);
	
	
	    $mustache = array(
        	'{{pushResposta}}' => ' OK, Maestro'
        );
        
        // Render View
        $this->render($mustache, $this->controller, $this->viewName, $this->view->header);
    }

    public function enviarEmail(){

    }
}