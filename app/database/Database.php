<?php

declare(strict_types = 1);

namespace App\Database;

use Exception;
use PDO;
use PDOStatement;
use PDOException;
use RuntimeException;



interface DatabaseInterface
{
    public function connect(): PDO;
    public function query(string $sql, array $params = []): PDOStatement;
}



class Sqlite implements DatabaseInterface
{
    private ?PDO $pdo = null;

    public function connect(): PDO
    {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO("sqlite:app/database/db.sqlite"); // Use forward slashes for portability
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }

        return $this->pdo;
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        if ($this->pdo === null) {
            $this->connect();
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}





class Database
{
    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        if (empty($sql) || trim($sql) === '') {
            throw new \InvalidArgumentException("SQL query cannot be empty.");
        }
        return $this->db->query($sql, $params);
    }
}


