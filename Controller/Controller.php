<?php


namespace Controller;


use Model\Core\View as View;
use Model\Core\De as de;

class Controller
{

    /* Object VIEW / Layout */
    public $view;

    /* Name any action */
    public $viewName;

    public function __construct()
    {
        $this->view = new View();
    }

}