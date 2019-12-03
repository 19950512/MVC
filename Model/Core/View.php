<?php


namespace Model\Core;


class View
{
    public $header = [];

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
        foreach ($this->header as $tag_name => $content){
            $headers .= '<meta name="'. $tag_name.'" content="'.$content.'">'.PHP_EOL;
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
    public function setHeader(array $header): void
    {
        $this->header = $header;
    }
}