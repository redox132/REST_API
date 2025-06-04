<?php

namespace App\Controllers;

use App\Models\Model;
use App\Database\Database;

class Controller
{

    static public function getAll($table)
    {
        
        $res = Model::getAll($table);

         if (empty($res)) {
            http_response_code(404);
            echo json_encode([
                'status' => "404",
                'message' => "No data found || table is empty"
            ], JSON_PRETTY_PRINT);
            die();
        }
        
        echo json_encode(
            ['data' => $res
        ], JSON_PRETTY_PRINT);
    }
    
    static public function getOne(string $table, string $id)
    {
        $res = Model::getOne($table, $id);


        
        if (empty($res)) {
            http_response_code(404);
            echo json_encode([
                'status' => "404",
                'message' => "No data found for the ID: $id"
            ], JSON_PRETTY_PRINT);
            die();
        }
        
        echo json_encode([
            'data' => $res
        ], JSON_PRETTY_PRINT);
    }

    static public function store(string $table, array $data) 
    {
        $res = Model::store($table, $data);

          echo json_encode([
            'status' => '200',
            'ID' => Database::connect()->lastInsertId()
        ], JSON_PRETTY_PRINT);
        
    }
    
    static public function patch(string $table, array $data, string $id) 
    {
        $res = Model::patch($table, $data, $id);
        
        echo json_encode([
            'status' => '200',
            'Affected  ID: ' => $id
        ], JSON_PRETTY_PRINT);

    }

    static public function delete(string $table, string $id)
    {
        
        $fetchedId = Database::query("SELECT * FROM $table WHERE id = ?", [$id])->fetch();
        
        if (empty($fetchedId)) {
            
            http_response_code(404);
            
            echo json_encode([
                'status' => '404',
                'message' => "the ID: $id you are trying to delete does not exist"
            ], JSON_PRETTY_PRINT);
            
            exit;
        }
        
        Model::delete($table, $id);

        echo json_encode([
            'status' => '200',
            'Affected  ID: ' => $id
        ], JSON_PRETTY_PRINT);
    }

}