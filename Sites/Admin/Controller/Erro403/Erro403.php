<?php


namespace Sites\Admin\Controller\Erro403;

use Sites\Admin\Controller\Controller;
use Model\Core\View as View;
use Model\Core\De as De;

class Erro403 extends Controller
{
    protected $controller = 'Erro403';

    public function __construct()
    {
        parent::__construct();

        header('HTTP/1.0 403 Forbidden');
    }

    public function index(){
        $this->viewName = 'Erro403';

        $this->view->setHeader([
	        ['name' => 'robots', 'content' => 'noindex, nofollow'],
	        ['name' => 'author', 'content' => 'DevNux'],
        ]);
        
        $mustache = array();
        
	    // Render View
	    $this->render($mustache, $this->controller, $this->viewName);
    }
}