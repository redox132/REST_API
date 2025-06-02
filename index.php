<?php
header("Content-Type: application/json");
require_once 'src/ItemController.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

if ($uri[0] !== 'items') {
    http_response_code(404);
    echo json_encode(["error" => "Not Found"]);
    exit;
}

$id = $uri[1] ?? null;

switch ($method) {
    case 'GET':
        $id ? ItemController::show($id) : ItemController::index();
        break;
    case 'POST':
        ItemController::store();
        break;
    case 'PUT':
        if ($id) ItemController::update($id);
        break;
    case 'DELETE':
        if ($id) ItemController::destroy($id);
        break;
    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}
