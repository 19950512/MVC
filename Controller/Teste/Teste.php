<?php


namespace Controller\Teste;


use Controller\Controller;
use Model\Core\View as View;

class Teste extends Controller
{
    protected $controller = 'Teste';

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){

        $this->viewName = 'Teste';

        $mustache = array();

        echo $this->view->mustache($mustache, VIEW::getView($this->controller, $this->viewName));
        exit;
    }

    public function otheraction(){

        $this->viewName = 'Otheraction';

        $mustache = array();

        echo $this->view->mustache($mustache, VIEW::getView($this->controller, $this->viewName));
        exit;
    }
}