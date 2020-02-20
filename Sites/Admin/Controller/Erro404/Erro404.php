<?php


namespace Sites\Admin\Controller\Erro404;

use Sites\Admin\Controller\Controller;
use Model\Core\View as View;
use Model\Core\De as De;

class Erro404 extends Controller
{
    protected $controller = 'Erro404';

    public function __construct()
    {
        parent::__construct();

        header("HTTP/1.0 404 Not Found");
    }

    public function index(){
        $this->viewName = 'Erro404';

        $this->view->setHeader([
	        ['name' => 'robots', 'content' => 'noindex, nofollow'],
	        ['name' => 'author', 'content' => 'DevNux'],
        ]);
        
        $mustache = array();
        
	    // Render View
	    $this->render($mustache, $this->controller, $this->viewName);
    }
}