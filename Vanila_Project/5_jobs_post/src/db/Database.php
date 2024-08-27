<?php

namespace DB;

// Database Singleton Class
class Database
{
    private $dbh = null;
    private static $instance = null;

    private function __construct()
    {
        $config = $GLOBALS['config'];

        $dsn = "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']};charset={$config['DB_CHARSET']}";

        $this->dbh = new \PDO($dsn, $config['DB_USERNAME'], $config['DB_PASSWORT'], $config['DB_OPTIONS']);
    }
    private static function GetConnection()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->dbh;
    }

    public static function Query($query, $params = [])
    {
        $stmt = self::GetConnection()->prepare($query);
        if(count($params) > 0) {
            for ($i=1; $i <= count($params); $i++) {
                $stmt->bindValue($i, $params[$i - 1]);
            }
        }
        $stmt->execute();

        return $stmt;
    }
}
