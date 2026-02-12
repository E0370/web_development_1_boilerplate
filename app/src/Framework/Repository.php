<?php
namespace App\Framework;

use App\Config;
use PDO;
use PDOException;

class Repository{
    protected static ?PDO $connection = null;
    protected function getConnection(): PDO{
        if(self::$connection == null){
            $this->connect();
        }

        return self::$connection;
    }

    private function connect(){
        try{
            $connectionString = 'mysql:host=' . Config::DB_SERVER_NAME . ';dbname=' . Config::DB_NAME . ';charset=utf8mb4';

            //create PDO connection
            self::$connection = new PDO($connectionString, Config::DB_USERNAME, Config::DB_PASSWORD);

            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }
    
}
?>