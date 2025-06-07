<?php

namespace App\Controllers;

use App\Models\Model;
use App\Database\Sqlite;
use App\Database\Database;
use App\Helpers\Validator;
use App\Controllers\Jwt\Jwt;

class Controller
{
    static private ?Database $db = null;

    // Initialize Database instance once
    static private function getDb(): Database
    {
        if (self::$db === null) {
            self::$db = new Database(new Sqlite());
        }
        return self::$db;
    }

    static public function getAll(string $table)
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
            exit;
        }

        echo json_encode([
            'page' => $page,
            'limit' => $limit,
            'data' => $res
        ], JSON_PRETTY_PRINT);
    }

    static public function getOne(string $table, ?string $id, ?string $email = null)
    {
        $email = $_GET['email'] ?? $email;

        if (isset($email)) {
            $res = Model::getOne($table, $id, $email);
        } else {
            $res = Model::getOne($table, $id);
        }

        if (empty($res)) {
            http_response_code(404);
            $msg = isset($email)
                ? "No data found for the email: $email"
                : "No data found for the ID: $id";

            echo json_encode([
                'status' => "404",
                'message' => $msg
            ], JSON_PRETTY_PRINT);
            exit;
        }

        echo json_encode(['data' => $res], JSON_PRETTY_PRINT);
    }

    static public function store(string $table, array $data)
    {
        $requiredFields = ['name', 'email', 'password'];
        $missing = Validator::validateRequired($data, $requiredFields);

        if (!empty($missing)) {
            http_response_code(400);
            echo json_encode([
                'status' => '400',
                'message' => 'Missing required fields',
                'missing_fields' => $missing
            ], JSON_PRETTY_PRINT);
            exit;
        }

        // Sanitize input
        $data = Validator::sanitizeArray($data);

        // Validate email format
        if (!Validator::validateEmail($data['email'])) {
            http_response_code(422);
            echo json_encode([
                'status' => '422',
                'message' => 'Invalid email format'
            ], JSON_PRETTY_PRINT);
            exit;
        }

        // Check if email already exists
        $existing = self::getDb()->query("SELECT * FROM $table WHERE email = ?", [$data['email']])->fetch();

        if ($existing) {
            http_response_code(409); // Conflict
            echo json_encode([
                'status' => '409',
                'message' => 'Email already registered'
            ], JSON_PRETTY_PRINT);
            exit;
        }

        // Hash password before storing
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        Model::store($table, $data);

        $lastId = self::getDb()->query("SELECT last_insert_rowid() AS id")->fetch()['id'];

        echo json_encode([
            'status' => '200',
            'ID' => $lastId,
        ], JSON_PRETTY_PRINT);
    }

    static public function patch(string $table, array $data, string $id)
    {
        Model::patch($table, $data, $id);

        echo json_encode([
            'status' => '200',
            'Affected ID' => $id
        ], JSON_PRETTY_PRINT);
    }

    static public function delete(string $table, string $id)
    {
        $fetched = self::getDb()->query("SELECT * FROM $table WHERE id = ?", [$id])->fetch();

        if (empty($fetched)) {
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

    static public function login(array $data)
    {
        $user = Model::login($data);

        if (!$user) {
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'message' => "No data was found for the provided email: " . $data['email']
            ], JSON_PRETTY_PRINT);
            exit;
        }

        if (password_verify($data['password'], $user['password'])) {
            $token = Jwt::generateToken([
                'id' => $user['id'],
                'email' => $user['email']
            ]);

            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'message' => "You have been logged in",
                'token' => $token
            ], JSON_PRETTY_PRINT);
            exit;
        }

        http_response_code(403);
        echo json_encode([
            'status' => 403,
            'message' => "Invalid credentials"
        ], JSON_PRETTY_PRINT);
    }
}
