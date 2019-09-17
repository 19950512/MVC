<?php

namespace Model\Db;

class Connection
{
    public static $instance;

    public static function getConnection()
    {

        try {

            if (!isset(self::$instance)) {
                self::$instance = new \PDO('pgsql:host = ' . DB_HOST . ' dbname = ' . DB_NAME . ' user = ' . DB_USER . ' password = ' . DB_PASSWORD . ' port =' . DB_PORT);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }

            return self::$instance;

        } catch (PDOException $e){

            return 'Error connection';
            exit;
        }
    }
}