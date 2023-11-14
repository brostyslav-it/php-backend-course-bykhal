<?php
require_once "fileOperations.php";
require_once "loadHeaders.php";
header("Access-Control-Allow-Methods: POST");
require_once "optionsCheck.php";

const COUNTER_FILE_PATH = "idCounter.txt";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method allowed"]);
    exit();
}

$requestBody = json_decode(file_get_contents("php://input"), true);
$items = json_decode(readFileData(), true)["items"];

if (!isset($requestBody["text"]) or empty($requestBody["text"])) {
    http_response_code(400);
    echo json_encode(["error" => "No data given"]);
    exit();
}

$id = intval(readFileData(COUNTER_FILE_PATH));

$items[] = [
    "id" => ++$id,
    "text" => $requestBody["text"],
    "checked" => false
];

writeToFile($id, COUNTER_FILE_PATH);
echo json_encode(["id" => $id]);

writeToFile(json_encode(["items" => $items]));
http_response_code(201);
