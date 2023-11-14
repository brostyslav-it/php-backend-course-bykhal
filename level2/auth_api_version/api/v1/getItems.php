<?php
global $db;
require_once "loadHeaders.php";
require_once "connection.php";
header("Access-Control-Allow-Methods: GET");

session_start();

if (!isset($_SESSION["userId"])) {
    http_response_code(401);
    echo json_encode(["error" => "You must log in to get items"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    http_response_code(405);
    echo json_encode(["error" => "Only GET method allowed"]);
    exit();
}

$items = $db->prepare("SELECT * FROM items WHERE userId = ?");
$items->execute([$_SESSION["userId"]]);

echo json_encode(["items" => $items->fetchAll(PDO::FETCH_ASSOC)]);

$items = null;
$db = null;
