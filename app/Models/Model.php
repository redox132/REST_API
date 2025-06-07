<?php

namespace App\Models;

use App\Database\Database;
use App\Database\Sqlite;
use PDO;

class Model
{
    // Make $db static and initialize it once on first use
    static private ?Database $db = null;

    // Initialize Database once
    static private function getDb(): Database
    {
        if (self::$db === null) {
            self::$db = new Database(new Sqlite());
        }
        return self::$db;
    }

    static public function getAll(string $table, int $page = 1, int $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        // PDO doesn't support LIMIT and OFFSET binding in some drivers (like SQLite).
        // So cast and insert directly after validating they're integers.
        $limit = (int)$limit;
        $offset = (int)$offset;

        $sql = "SELECT * FROM $table LIMIT $limit OFFSET $offset";

        $stmt = self::getDb()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function getOne(string $table, ?string $id, ?string $email = null)
    {
        if (isset($email)) {
            $stmt = self::getDb()->query("SELECT * FROM $table WHERE email = ?", [$email]);
        } else {
            $stmt = self::getDb()->query("SELECT * FROM $table WHERE id = ?", [$id]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function store(string $table, array $data)
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $columnsList = implode(',', $columns);

        $sql = "INSERT INTO $table ($columnsList) VALUES ($placeholders)";

        self::getDb()->query($sql, $values);

        // Return last inserted ID
        $stmt = self::getDb()->query("SELECT last_insert_rowid() AS id");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function patch(string $table, array $data, string $id)
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $set = implode(',', array_map(fn($col) => "$col = ?", $columns));

        $values[] = $id;

        $sql = "UPDATE $table SET $set WHERE id = ?";

        self::getDb()->query($sql, $values);

        // Return updated row
        return self::getOne($table, $id);
    }

    static public function delete(string $table, string $id)
    {
        self::getDb()->query("DELETE FROM $table WHERE id = ?", [$id]);
        return ['deleted_id' => $id];
    }

    static public function login(array $data)
    {
        $stmt = self::getDb()->query("SELECT * FROM users WHERE email = ?", [$data['email']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
