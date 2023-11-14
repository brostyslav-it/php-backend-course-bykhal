<?php
global $db;
require_once "loadHeaders.php";
require_once "connection.php";
header("Access-Control-Allow-Methods: GET");

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    http_response_code(405);
    echo json_encode(["error" => "Only GET method allowed"]);
    exit();
}

$items = $db->query("SELECT * FROM items")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(["items" => $items]);
