<?php
require_once 'src/Database.php';

class ItemController {
    public static function index() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM items");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function show($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $item ? json_encode($item) : json_encode(["error" => "Not found"]);
    }

    public static function store() {
        $data = json_decode(file_get_contents("php://input"), true);
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO items (name, description) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['description']]);
        echo json_encode(["success" => true, "id" => $db->lastInsertId()]);
    }

    public static function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE items SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['description'], $id]);
        echo json_encode(["success" => true]);
    }

    public static function destroy($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM items WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true]);
    }
}
