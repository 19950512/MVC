<?php

namespace Model\Core;

class Core {

    public static function mustache($mustache = [], $mask = ''){
        $view = str_replace(array_keys($mustache), array_values($mustache), $mask);
        return $view;
    }
}