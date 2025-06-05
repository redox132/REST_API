<?php

namespace App\Models;

use App\Database\Database;

class Model
{
   static public function getAll(string $table, int $page = 1, int $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM $table LIMIT :limit OFFSET :offset";

        $stmt = Database::query($sql, [
            ':limit' => $limit,
            ':offset' => $offset
        ])->fetchAll();
        
        return $stmt;
    }

    static public function getOne(string $table, ?string $id, ?string $email = null)
    {
        if (isset($email)) {
            return Database::query("SELECT * FROM $table WHERE email = ?", [$email])->fetch();
        }else{
            return Database::query("SELECT * FROM $table WHERE id = ?", [$id])->fetch();
        }
    }
    
    static public function store(string $table, array $data) {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $sql = "INSERT INTO $table (" . implode(",", $columns) . ") VALUES (" . implode(",", array_fill(0, count($values), "?")) . ")";
        
        return Database::query($sql, $values)->fetch();
        
    }

    static public function patch(string $table, array $data, string $id)
    {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $set = implode(',', array_map( fn($col) => "$col = ?", $columns));

        $values[] = $id;

        $sql = "UPDATE $table set $set WHERE id = ?";
        
        return Database::query($sql, $values)->fetch();
        
    }
    
    static public function delete(string $table, string $id)
    {
        return Database::query("DELETE FROM $table WHERE id = ?", [$id])->fetch();
    }

    static public function login(array $data) 
    {
        return Database::query("SELECT * FROM users WHERE email = ?", [$data['email']])->fetch();
    }
}