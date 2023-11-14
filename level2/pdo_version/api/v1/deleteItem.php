<?php
global $db;
require_once "loadHeaders.php";
require_once "idChecker.php";
header("Access-Control-Allow-Methods: DELETE");
require_once "optionsCheck.php";

if ($_SERVER["REQUEST_METHOD"] != "DELETE") {
    http_response_code(405);
    echo json_encode(["error" => "Only DELETE method allowed"]);
    exit();
}

$requestBody = json_decode(file_get_contents("php://input"), true);

if (!isset($requestBody["id"])) {
    http_response_code(400);
    echo json_encode(["error" => "No ID given"]);
    exit();
}

if (!is_numeric($requestBody["id"])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid ID"]);
    exit();
}

if (!isIdExists($requestBody["id"])) {
    http_response_code(400);
    echo json_encode(["error" => "No element with ID $requestBody[id]"]);
} else {
    $deleteQuery = $db->prepare("DELETE FROM items WHERE id = ?");
    $deleteQuery->execute([$requestBody["id"]]);

    $updateId = $db->prepare("UPDATE items SET id = id - 1 WHERE id > ?");
    $updateId->execute([$requestBody["id"]]);
    $db->query("ALTER TABLE items AUTO_INCREMENT = 1");

    $deleteQuery = null;
    $updateId = null;
    $db = null;

    echo json_encode(["ok" => true]);
}
