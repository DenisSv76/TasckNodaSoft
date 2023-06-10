<?php

namespace Gateway;

use PDO;

class Connect
{
    /**
     * @var PDO
     */
    private static $instance;

    /**
     * Реализация singleton
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$instance)) {
            $dsn = 'mysql:dbname=db;host=127.0.0.1';
            $user = 'dbuser';
            $password = 'dbpass';
            self::$instance = new PDO($dsn, $user, $password);
        }

        return self::$instance;
    }
}