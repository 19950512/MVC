<?php


namespace Sites\Central\Controller\Erro404;

use Sites\Central\Controller\Controller;
use Model\Core\View as View;
use Model\Core\De as De;

class Erro404 extends Controller
{
    protected $controller = 'Erro404';

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->viewName = 'Erro404';

        $this->view->setHeader([
	        ['name' => 'robots', 'content' => 'noindex, nofollow'],
	        ['name' => 'author', 'content' => 'Central'],
        ]);
        
        $mustache = array();
        
	    // Render View
	    $this->render($mustache, $this->controller, $this->viewName);
    }

}