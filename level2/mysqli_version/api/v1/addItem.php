<?php
global $mysqli;
require_once "loadHeaders.php";
require_once "connection.php";
header("Access-Control-Allow-Methods: POST");
require_once "optionsCheck.php";

const COUNTER_FILE_PATH = "idCounter.txt";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method allowed"]);
    exit();
}

$requestBody = json_decode(file_get_contents("php://input"), true);

if (!isset($requestBody["text"]) or empty($requestBody["text"])) {
    http_response_code(400);
    echo json_encode(["error" => "No data given"]);
    exit();
}

$addItemQuery = $mysqli->prepare("INSERT INTO items (text) VALUES (?)");
$addItemQuery->execute([$mysqli->real_escape_string($requestBody["text"])]);

http_response_code(201);
echo json_encode(["id" => $mysqli->insert_id]);
$mysqli->close();