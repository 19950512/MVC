<?php


namespace Controller\Erro404;

use Controller\Controller;
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
	        ['name' => 'robots', 'content' => 'noindex, 2wwwwwwwwwwwww'],
	        ['name' => 'author', 'content' => 'DevNux'],
        ]);
        
        $mustache = array();

        echo $this->view->mustache($mustache, VIEW::getView($this->controller, $this->viewName));
        exit;
    }

}