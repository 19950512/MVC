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
    
    public $pushHistory = false;

    public function __construct()
    {
    	
    	if(isset($_POST['push']) and $_POST['push'] === 'push'){
    		$this->pushHistory = true;
	    }
    	
        $this->view = new View();
    }

    
    public function render($mustache, $controller, $viewName){
	   
	    /* Se for por F5 */
	    if($this->pushHistory === false){
		
		    echo $this->view->mustache($mustache, VIEW::getView($controller, $viewName));
		    exit;
		/* Se for por pushHistory */
	    }else{
	    	
		    $result['html'] = VIEW::getView($controller, $viewName);
		
		    echo json_encode($result);
		    exit;
	    }
    }
}