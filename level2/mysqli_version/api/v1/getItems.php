<?php
global $mysqli;
require_once "loadHeaders.php";
require_once "connection.php";
header("Access-Control-Allow-Methods: GET");

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    http_response_code(405);
    echo json_encode(["error" => "Only GET method allowed"]);
    exit();
}

$items = $mysqli->prepare("SELECT * FROM items");
$items->execute();
echo json_encode(["items" => $items->get_result()->fetch_all(MYSQLI_ASSOC)]);
