<?php
global $db;
require_once "loadHeaders.php";
require_once "connection.php";
header("Access-Control-Allow-Methods: POST");
require_once "optionsCheck.php";

const MAX_LOGIN_LENGTH = 30;
const MAX_PASS_LENGTH = 50;

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Only POST method allowed"]);
    exit();
}

$requestBody = json_decode(file_get_contents("php://input"), true);

if (!isset($requestBody["login"], $requestBody["pass"]) or empty($requestBody["login"]) or empty($requestBody["pass"])) {
    http_response_code(400);
    echo json_encode(["error" => "No data given"]);
    exit();
}

if (mb_strlen($requestBody["login"]) > MAX_LOGIN_LENGTH) {
    http_response_code(400);
    echo json_encode(["error" => "Login is too long, must be maximum " . MAX_LOGIN_LENGTH . " characters"]);
    exit();
}

if (mb_strlen($requestBody["pass"]) > MAX_PASS_LENGTH) {
    http_response_code(400);
    echo json_encode(["error" => "Password is too long, must be maximum " . MAX_PASS_LENGTH . " characters"]);
    exit();
}

$findUsernameQuery = $db->prepare("SELECT * FROM users WHERE login = ?");
$findUsernameQuery->execute([$requestBody["login"]]);

if ($findUsernameQuery->rowCount() !== 0) {
    http_response_code(400);
    echo json_encode(["error" => "User with login \"$requestBody[login]\" already exists!"]);
    exit();
}

$addUserQuery = $db->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
$addUserQuery->execute([$requestBody["login"], password_hash($requestBody["pass"], PASSWORD_DEFAULT)]);

http_response_code(201);
echo json_encode(["ok" => true]);

$addUserQuery = null;
$db = null;
