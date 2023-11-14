<?php
require_once "fileOperations.php";
require_once "loadHeaders.php";
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

$id = $requestBody["id"];

if (!is_numeric($id)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid ID"]);
    exit();
}

$items = json_decode(readFileData(), true)["items"];
$elementIndex = array_search($id, array_column($items, "id"));

if ($elementIndex === false) {
    http_response_code(400);
    echo json_encode(["error" => "No element with ID $id"]);
} else {
    array_splice($items, $elementIndex, 1);
    writeToFile(json_encode(["items" => $items]));
    echo json_encode(["ok" => true]);
}
