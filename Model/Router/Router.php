<?php


namespace Model\Router;
USE Model\Core\De AS de;


class Router
{

    public $controller = 'Index';
    public $action = 'index';
    public $namespace = 'Controller\Index\Index';
    public $url;

    public $file_controller;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        if(isset($_SERVER['REQUEST_URI']) and !empty($_SERVER['REQUEST_URI'])){

            $url = $this->parseURL($_SERVER['REQUEST_URI']);

            // Atualiza a URL
            $this->setUrl($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
            $controller = ucwords(strtolower($url[1] ?? ''));
            $action = strtolower($url[2] ?? '');
            $this->file_controller = CONTROLLER . DS . $controller . 'Index/Index.php';

            // If controller !== ''
            if(!empty($controller)) {

                $this->setController($controller);
                $this->setFileController($controller);
                $this->setNamespace($controller.'\\'.$controller);

                $fileController = CONTROLLER . DS . $controller . DS . $controller . '.php';
                // If not exists Controller || not exists action/method = Erro404
                if(!class_exists($this->namespace) OR !is_file($fileController)){
                    $this->set404();
                }
            }

            // If exists action
            if(isset($action) and !empty($action)){
                $this->setAction($action);
            }
        }
    }

    public function set404(){
        $this->setController('Erro404');
        $this->setFileController('Erro404');
        $this->setNamespace('Erro404\Erro404');
        $this->setAction('index');
    }

    private function parseURL($url){

        $array = explode('/', $url);
        $temp = array();

        foreach ($array as $key => $value) {

            $temp[$key] = preg_replace('/\?.*$|\!.*$|#.*$|(?# \'.*$|)\@.*$|\$.*$|&.*$|\*.*$|\+.*$|\..*$/', '', $value);
        }

        return $temp;
    }


    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     * @return Router
     */
    public function setNamespace($namespace)
    {
        $this->namespace = 'Controller\\'.$namespace;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getFileController()
    {
        return $this->file_controller;
    }

    /**
     * @param mixed $file_controller
     * @return Router
     */
    public function setFileController($file_controller)
    {
        $this->file_controller = CONTROLLER . DS . $file_controller . DS . $file_controller . '.php';
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return Router
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller): void
    {
        $this->controller = $controller;
    }

}