<?php
require_once "fileOperations.php";
require_once "loadHeaders.php";
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

$items = json_decode(readFileData(), true)["items"];
$elementIndex = array_search($requestBody["id"], array_column($items, "id"));

if ($elementIndex === false) {
    http_response_code(400);
    echo json_encode(["error" => "No element with ID $requestBody[id]"]);
} else {
    $items[$elementIndex] = [
        "id" => $items[$elementIndex]["id"],
        "text" => $requestBody["text"],
        "checked" => $requestBody["checked"]
    ];

    writeToFile(json_encode(["items" => $items]));
    echo json_encode(["ok" => true]);
}
