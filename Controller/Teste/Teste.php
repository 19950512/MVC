<?php


namespace Controller\Teste;


use Model\Core\View as View;
use Model\Core\De as de;

class Teste
{
    protected $controller = 'Teste';

    public function __construct()
    {
    }


    public function index(){

        $viewMethod = 'Teste';

        $view = new View();
        $view->setHeader([
            'description' => 'MVC PHP 7.x - Maydana',
            'robots' => 'index, follow',
            'author' => 'Matheus Maydana'
        ]);

        $mustache = array();

        echo $view->mustache($mustache, VIEW::getView($this->controller, $viewMethod));
        exit;
    }

    public function otheraction(){

        $viewMethod = 'Otheraction';

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