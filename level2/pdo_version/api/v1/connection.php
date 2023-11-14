<?php
const HOST = "localhost";
const DB_NAME = "todo_items";
const ITEMS_TABLE_NAME = "items";
const USER = "root";
const PASSWORD = "";

try {
    $db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASSWORD);
    $db->query("CREATE TABLE IF NOT EXISTS " . ITEMS_TABLE_NAME
        . "(id INT AUTO_INCREMENT NOT NULL PRIMARY KEY, text TEXT NOT NULL, checked BOOL DEFAULT FALSE)");
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(["error" => $exception->getMessage()]);
}
