<?php

namespace App\Database;
use PDO;
use throwable;

class Database 
{

    static private ?PDO $pdo = null;

    public function __construct() 
    {
        if (self::$pdo == null) 
        {
            try 
            {
                self::$pdo = new PDO("sqlite:app/database/db.sqlite");
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (Throwable $th) {
                throw $th;
            }
        }
    }

    static public function connect() :PDO
    {
        if (self::$pdo === null) 
        {
            new self();
        }

        return self::$pdo;
    }
    
    static public function query(string $sql, array $params = []) :\PDOStatement
    {

        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;

    }
}
