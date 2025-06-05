<?php

namespace App\Controllers;

use App\Models\Model;
use App\Database\Database;
use App\Helpers\Validator;

class Controller
{
    static public function getAll($table)
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

        $res = Model::getAll($table, $page, $limit);

        if (empty($res)) {
            http_response_code(404);
            echo json_encode([
                'status' => "404",
                'message' => "No data found || table is empty"
            ], JSON_PRETTY_PRINT);
            die();
        }

        echo json_encode([
            'page' => $page,
            'limit' => $limit,
            'data' => $res
        ], JSON_PRETTY_PRINT);
    }

    static public function getOne(string $table, ?string $id, ?string $email = null)
    {
        $email = isset($_GET['email']) ? $_GET['email'] : $email;

        if (isset($email)) {
            $res = Model::getOne($table, $id, $email);
        } else {
            $res = Model::getOne($table, $id);
        }

        if (empty($res) && !isset($email)) {
            http_response_code(404);
            echo json_encode([
                'status' => "404",
                'message' => "No data found for the ID: $id"
            ], JSON_PRETTY_PRINT);
            die();
        } elseif (empty($res) && isset($email)) {
            http_response_code(404);
            echo json_encode([
                'status' => "404",
                'message' => "No data found for the email: $email"
            ], JSON_PRETTY_PRINT);
            die();
        }

        echo json_encode([
            'data' => $res
        ], JSON_PRETTY_PRINT);
    }

    static public function store(string $table, array $data) 
    {
        // Basic required fields for demonstration
        $requiredFields = ['name', 'email'];
        $missing = Validator::validateRequired($data, $requiredFields);

        if (!empty($missing)) {
            http_response_code(400);
            echo json_encode([
                'status' => '400',
                'message' => 'Missing required fields',
                'missing_fields' => $missing
            ], JSON_PRETTY_PRINT);
            return;
        }

        // Sanitize data
        $data = Validator::sanitizeArray($data);

        // Validate email
        if (!Validator::validateEmail($data['email'])) {
            http_response_code(422);
            echo json_encode([
                'status' => '422',
                'message' => 'Invalid email format'
            ], JSON_PRETTY_PRINT);
            return;
        }

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
            'Affected ID' => $id
        ], JSON_PRETTY_PRINT);
    }

    static public function delete(string $table, string $id)
    {
        $fetchedId = Database::query("SELECT * FROM $table WHERE id = ?", [$id])->fetch();

        if (empty($fetchedId)) {
            http_response_code(404);
            echo json_encode([
                'status' => '404',
                'message' => "The ID: $id you are trying to delete does not exist"
            ], JSON_PRETTY_PRINT);
            exit;
        }

        Model::delete($table, $id);

        echo json_encode([
            'status' => '200',
            'Affected ID' => $id
        ], JSON_PRETTY_PRINT);
    }
}
