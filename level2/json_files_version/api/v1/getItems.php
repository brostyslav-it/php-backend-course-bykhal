<?php
require_once "fileOperations.php";
require_once "loadHeaders.php";
header("Access-Control-Allow-Methods: GET");

if ($_SERVER["REQUEST_METHOD"] != "GET") {
    http_response_code(405);
    echo json_encode(["error" => "Only GET method allowed"]);
    exit();
}

echo readFileData();
