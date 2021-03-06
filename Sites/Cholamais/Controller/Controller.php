<?php


namespace Sites\Cholamais\Controller;

use Model\Core\View as View;
use Model\Core\De as de;
use Model\Visitante\Visitante;

class Controller
{

    /* Object VIEW / Layout */
    public $view;

    /* Name any action */
    public $viewName;
    
    public $pushHistory = false;

    /* Object Visitante / Class Visitante */
    public $visitante;

    public function __construct()
    {
    	
    	if(isset($_POST['push']) and $_POST['push'] === 'push'){
    		$this->pushHistory = true;
	    }

		/*
			Estrutura do array para salvar o visitante

			$_SESSION[SESSION_VISITANTE] = [
				'vis_nome' => 'Matheus Maydana',
				'vis_tel' => '(54) 3342-4545',
				'vis_cel' => '(54) 9 2000-6794',
				'vis_email' => 'email@matheus.com',
				'vis_ip' => $_SERVER['REMOTE_ADDR'],
			];
		*/

		// instancia-se a classe para registrar o visitante novo
		$this->visitante = new Visitante();

        $this->view = new View();
    }

    
    public function render($mustache = [], $controller = '', $viewName = '', $metas = [], $layout = 'Layout'){
	   
	    /* Se for por F5 */
	    if($this->pushHistory === false){
		
		    echo $this->view->mustache($mustache, $this->view->getView($controller, $viewName), $layout);
		    exit;
		    
	    }else{

		    /* Se for por pushHistory */
		    $result['html'] = $this->view->pushHistory($mustache, $this->view->getView($controller, $viewName), $layout);
		    $result['metas'] = [
		    	'title' => $this->view->title,
			    'description' => array_filter($this->view->header, fn($x) => ($x['name'] === 'description') ? $x['content'] : '')[1]['content']
		    ];

		    echo json_encode($result);
		    exit;
	    }
    }
}