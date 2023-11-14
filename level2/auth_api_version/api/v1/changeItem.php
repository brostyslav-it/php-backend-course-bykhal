<?php
global $db;
require_once "loadHeaders.php";
require_once "idChecker.php";
header("Access-Control-Allow-Methods: PUT");
require_once "optionsCheck.php";

if ($_SERVER["REQUEST_METHOD"] != "PUT") {
    http_response_code(405);
    echo json_encode(["error" => "Only PUT method allowed"]);
    exit();
}

session_start();

if (!isset($_SESSION["userId"])) {
    http_response_code(401);
    echo json_encode(["error" => "You must log in to change items"]);
    exit();
}

$requestBody = json_decode(file_get_contents("php://input"), true);

if (!isset($requestBody["id"], $requestBody["text"], $requestBody["checked"]) or empty($requestBody["text"])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid data"]);
    exit();
}

if (!is_numeric($requestBody["id"])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid ID"]);
    exit();
}

if (!is_bool($requestBody["checked"]) && !is_numeric($requestBody["checked"])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid value of checked"]);
    exit();
}

if (!isIdExists($requestBody["id"], $_SESSION["userId"])) {
    http_response_code(400);
    echo json_encode(["error" => "Element id doesn't match for current user"]);
} else {
    $changeQuery = $db->prepare("UPDATE items SET text = ?, checked = ? WHERE id = ?");
    $changeQuery->execute([
        $requestBody["text"],
        (int) $requestBody["checked"],
        $requestBody["id"]
    ]);

    $changeQuery = null;
    $db = null;

    echo json_encode(["ok" => true]);
}
