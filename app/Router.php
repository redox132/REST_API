<?php

namespace App;

use App\Controllers\Controller;

class Router
{

    static private $allowedTables = ['users', 'products'];

    static public function route()
    
    {

        
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");
        $table = explode("/", $uri)[0];
        $id = explode("/", $uri)[1] ?? null;
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true) ?? [];
        
        if (!in_array($table, self::$allowedTables)) 
        {
            throw new \Exception("Rida says the table does not exist");
        }
        
        switch($method) {
            case "GET":
                $id ? Controller::getOne($table, $id) : Controller::getAll($table) ;
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

                echo json_encode([
                    'status' => 'failed',
                    'message' => 'Unknown request method'
                ]);
                break;


        }

        return $id;
    }

}