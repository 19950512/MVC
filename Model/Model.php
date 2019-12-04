<?php


namespace Model;


class Model
{
    public $conexao;
    public function __construct()
    {
        $this->conexao = Connection::getConnection();
    }
}