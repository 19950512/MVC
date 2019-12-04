<?php


namespace Model\Core;


class View
{
    /* Metas por Default */
    public $header = array(
    	//array('name' => 'charset', 'content' => 'UTF-8'),
        //array('name' => 'description', 'content' => 'MVC PHP 7.x - Maydana', 'other' => 'defer="defer"'),
        array('name' => 'robots', 'content' => 'index, follow', 'other' => 'sync="sync"'),
       // array('name' => 'http-equiv', 'content' => 'refresh'),
    );

    private function layout($layout = 'Layout'){
        $pathView = LAYOUT . DS . $layout . EXTENSAO_VIEW;
        $layoutView = file_exists($pathView) ? file_get_contents($pathView) : '';

        $mustache = array(
            '{{metas}}' => $this->_getHead()
        );

        $layout = str_replace(array_keys($mustache), array_values($mustache), $layoutView);
        return self::comprimeHTML($layout);
    }

    public function mustache($mustache = [], $view = '', $layout = 'Layout'){
        $view = str_replace(array_keys($mustache), array_values($mustache), $view);
        return str_replace('{{view}}', $view, $this->layout($layout));;
    }

    public static function getView($controlador = 'Index', $view = 'Index'){
        $pathView = VIEW . DS . $controlador . DS . $view . EXTENSAO_VIEW;
        return self::comprimeHTML(file_exists($pathView) ? file_get_contents($pathView) : '');
    }

    public static function getLayout($layout = 'Layout'){
        $pathView = LAYOUT . DS . $layout . EXTENSAO_VIEW;
        return file_exists($pathView) ? file_get_contents($pathView) : '';
    }

    private function _getHead(){
        $headers = '';
        if(is_array($this->header)){
	
	        foreach($this->header as $meta) {
		
		        $other = '';
		        if(isset($meta['other']) and !empty($meta['other'])){
			        $other = $meta['other'];
		        }
		
		        $headers .= '<meta '.$meta['name'].'="'.$meta['content'].'" '.$other.'>';
	        }
        }

        return $headers;
    }
    public static function comprimeHTML($html = ''){

        if(DEV === true){
            return $html;
        }

        $html = preg_replace(array("/\/\*(.*?)\*\//", "/<!--(.*?)-->/", "/\t+/"), ' ', $html);

        $mustache = array(
            "\t"		=> '',
            ""			=> ' ',
            PHP_EOL		=> '',
            '> <'		=> '><',
            '  '		=> '',
            '   '		=> '',
            '    '		=> '',
            '     '		=> '',
            '> <'		=> '><',
            '
'						=> ''
        );

        return str_replace(array_keys($mustache), array_values($mustache), $html);
    }
    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @param array $header
     */
    public function setHeader($array)
    {
	    $temp = [];
	    foreach ($array as $key => $meta){
		    $temp[$key] = $meta;
		    $temp[$key]['other'] = ($meta['other'] ?? '-');
	    }
	
		$this->header = array_merge($this->header, $array);
	    
	    new De($this->header);
    }
}