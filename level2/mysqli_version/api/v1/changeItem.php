<?php
global $mysqli;
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

if (!is_bool($requestBody["checked"])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid value of checked"]);
    exit();
}

if (!isIdExists($requestBody["id"])) {
    http_response_code(400);
    echo json_encode(["error" => "No element with ID $requestBody[id]"]);
} else {
    $changeQuery = $mysqli->prepare("UPDATE items SET text = ?, checked = ? WHERE id = ?");
    $changeQuery->execute([
        $mysqli->real_escape_string($requestBody["text"]),
        intval($requestBody["checked"]),
        $requestBody["id"]
    ]);
    $mysqli->close();

    echo json_encode(["ok" => true]);
}
