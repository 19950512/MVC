<?php


namespace Controller\Contato;

use Controller\Controller;
use Model\Core\View as View;

class Contato extends Controller
{

    protected $controller = 'Contato';

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->viewName = 'Contato';
	
	    $this->view->setHeader([
	    	['name' => 'robots', 'content' => '100 ROBOTS']
		   // ['name' => 'author', 'content' => 'GSTVara'],
		    //['name' => 'description', 'content' => 'Chat da Twitch é Brabo D+++']
	    ]);
	    
        $mustache = array(
        	'{{push}}' => $this->pushHistory
        );
        
        // Render View
        $this->render($mustache, $this->controller, $this->viewName);
    }

    public function enviarEmail(){

    }
}