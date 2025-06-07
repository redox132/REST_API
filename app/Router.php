<?php

namespace App;

use App\Controllers\Controller;
use App\Controllers\Jwt\Jwt;
use App\Logger\Logger;

class Router
{

    static private $allowedTables = ['users', 'products', 'login'];

    static public function route()
    {
        $logger = Logger::getLogger();

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");
        $segments = explode("/", $uri);
        $table = $segments[0];
        $id = $segments[1] ?? null;
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true) ?? [];
        $email = $_GET['email'] ?? null;


        // Log the request
        $logger->info('Incoming Request', [
            'time' => $_SERVER['REQUEST_TIME'],
            'method' => $method,
            'uri' => $_SERVER['REQUEST_URI'],
            'table' => $table,
            'id' => $id,
            'email' => $email,
            'body' => $data,
        ]);

        if (!in_array($table, self::$allowedTables)) {
            http_response_code(404);
            $logger->warning('Blocked table access attempt', ['table' => $table]);
            throw new \Exception("Rida says the table does not exist");
        }

        if ($uri == 'login' && $method == "POST") {
            Controller::login($data);
            die();
        }


        
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $jwt = str_replace('Bearer ', '', $authHeader);

        $payload = Jwt::verifyToken($jwt);

        // if (!$payload) {
        //     http_response_code(401);
        //     echo json_encode([
        //         'status' => 401,
        //         'message' => 'Unauthorized'
        //     ], JSON_PRETTY_PRINT);
        //     exit;
        // }elseif ($payload && $table == 'users' && $method == "POST") {
        //     http_response_code(403); 
        //     echo json_encode([
        //         'status' => 403,
        //         'message' => 'Authenticated users cannot register new accounts.'
        //     ], JSON_PRETTY_PRINT);
        //     exit;
        // }




        try {
            switch ($method) {
                case "GET":
                    ($id || $email)
                        ? Controller::getOne($table, $id, $email)
                        : Controller::getAll($table);
                    break;
                case "POST":
                    Controller::store($table, $data);
                    break;
                case "PATCH":
                    Controller::patch($table, $data, $id);
                    break;
                case "DELETE":
                    Controller::delete($table, $id);
                    break;
                default:
                    http_response_code(405);
                    $logger->error('Unsupported method', ['method' => $method]);

                    echo json_encode([
                        'status' => 'failed',
                        'message' => 'Unknown request method'
                    ]);
                    break;
            }
        } catch (\Throwable $e) {
            $logger->error('Routing error', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            http_response_code(500);
            echo json_encode([
                'status' => '500',
                'message' => 'Internal server error'
            ]);
        }

        return $id;
    }
}
