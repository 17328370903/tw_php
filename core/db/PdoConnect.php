<?php

namespace core\db;

class PdoConnect
{

    private static array $dbs = [];

    private function __construct()
    {
    }

    private function __clone()
    {

    }

    public static function getInstance(string $key)
    {
        $dbConfigs =  $GLOBALS['appConfig']['databases'];
        $dbNameKey = $key;
        if (in_array($dbNameKey, self::$dbs)) {
            return self::$dbs[$dbNameKey];
        } else {
            $db = self::connectDb($dbConfigs[$dbNameKey]);
            self::$dbs[$key] = $db;
        }
        return self::$dbs[$key];

    }


    private static function connectDb(array $config)
    {
        $host     = $config['host'] ?? '127.0.0.1';
        $user     = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $database = $config['dbname'] ?? '';
        $port     = $config['port'] ?? 3306;
        $charset  = $config['charset'] ?? 'utf8mb4';
        $dsn = "mysql:host={$host}:{$port};dbname={$database};charset={$charset}";

        $pdo = new \PDO($dsn,$user,$password,[\PDO::ATTR_PERSISTENT => true]);

        return $pdo;

    }

}