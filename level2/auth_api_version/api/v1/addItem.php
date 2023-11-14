<?php
global $db;
require_once "loadHeaders.php";
require_once "connection.php";
header("Access-Control-Allow-Methods: POST");
require_once "optionsCheck.php";

const COUNTER_FILE_PATH = "idCounter.txt";

session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method allowed"]);
    exit();
}

if (!isset($_SESSION["userId"])) {
    http_response_code(401);
    echo json_encode(["error" => "You must log in to add items"]);
    exit();
}

$requestBody = json_decode(file_get_contents("php://input"), true);

if (!isset($requestBody["text"]) or empty($requestBody["text"])) {
    http_response_code(400);
    echo json_encode(["error" => "No data given"]);
    exit();
}

$addItemQuery = $db->prepare("INSERT INTO items (text, userId) VALUES (?, ?)");
$addItemQuery->execute([$requestBody["text"], $_SESSION["userId"]]);

http_response_code(201);
echo json_encode(["id" => $db->lastInsertId()]);

$addItemQuery = null;
$db = null;
