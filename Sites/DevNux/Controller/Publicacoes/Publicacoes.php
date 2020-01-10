<?php


namespace Sites\DevNux\Controller\Publicacoes;

use Sites\DevNux\Controller\Controller;
use Model\Core\De as de;
use Model\Publicacoes\Publicacoes as Pub;

class Publicacoes extends Controller
{

    protected $controller = 'Publicacoes';

    protected $publicacoes = [];

    public function __construct()
    {
        parent::__construct();

        $this->publicacoes = new Pub(); 
    }

    public function index(){
	    $this->viewName = 'Publicacoes';
	
	    $this->view->setTitle('Titulo do Publicacoes');
	    $this->view->setHeader([
		    ['name' => 'description', 'content' => 'Publicações']
	    ]);
	
	    $mustache = array(
        	'{{container_publicacoes}}' => $this->publicacoes->getPublicacoes()
        );
        
        // Render View
        $this->render($mustache, $this->controller, $this->viewName, $this->view->header);
    }

    public function enviarEmail(){

    }
}