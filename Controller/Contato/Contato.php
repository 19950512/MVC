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
	    
        $mustache = array();

        echo $this->view->mustache($mustache, VIEW::getView($this->controller, $this->viewName));
        exit;
    }

    public function enviarEmail(){

    }
}