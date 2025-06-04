<?php

namespace App\Models;

use App\Database\Database;

class Model
{
    static public function getAll(string $table)
    {
        return Database::query("SELECT * FROM $table")->fetchAll();
    }

    static public function getOne(string $table, string $id)
    {
        return Database::query("SELECT * FROM $table WHERE id = ?", [$id])->fetch();
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
}