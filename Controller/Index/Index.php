<?php


namespace Controller\Index;


use Controller\Controller;
use Model\Core\View as View;

class Index extends Controller
{

    protected $controller = 'Index';

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->viewName = 'Index';

        $mustache = array(
            '{{teste}}' => ''
        );

        echo $this->view->mustache($mustache, VIEW::getView($this->controller, $this->viewName));
        exit;
    }
}