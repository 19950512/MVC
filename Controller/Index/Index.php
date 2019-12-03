<?php


namespace Controller\Index;


use Model\Core\View as View;

class Index
{

    protected $controller = 'Index';

    public function __construct()
    {
    }

    public function index(){
        $viewMethod = 'Index';

        $view = new View();

        $mustache = array(
            '{{teste}}' => 'ESSE É UM TESTE PARA MUSTACHES'
        );

        echo $view->mustache($mustache, VIEW::getView($this->controller, $viewMethod));
        exit;
    }
}