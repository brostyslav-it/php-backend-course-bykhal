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

$requestBody["checked"] = (int) $requestBody["checked"];

if (!isIdExists($requestBody["id"])) {
    http_response_code(400);
    echo json_encode(["error" => "No element with ID $requestBody[id]"]);
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
