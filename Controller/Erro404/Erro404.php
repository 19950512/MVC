<?php


namespace Controller\Erro404;

USE Model\Core\View AS View;
USE Model\Core\Core AS Core;
USE Model\Core\De AS de;

class Erro404
{
    protected $controller = 'Erro404';

    public function __construct()
    {
    }

    public function index(){
        $viewMethod = 'Erro404';

        $view = new View();
        $view->setHeader([
            'description' => 'MVC PHP 7.x - Maydana',
            'robots' => 'noindex, nofollow',
            'author' => 'Matheus Maydana'
        ]);

        $mustache = array();

        echo $view->mustache($mustache, VIEW::getView($this->controller, $viewMethod));
        exit;
    }

}