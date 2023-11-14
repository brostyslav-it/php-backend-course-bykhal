<?php
global $db;
require_once "loadHeaders.php";
require_once "connection.php";
header("Access-Control-Allow-Methods: POST");
require_once "optionsCheck.php";

const MAX_LOGIN_LENGTH = 30;
const MAX_PASS_LENGTH = 50;

session_start();

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

$userFindQuery = $db->prepare("SELECT * FROM users WHERE login = ?");
$userFindQuery->execute([$requestBody["login"]]);

$user = $userFindQuery->fetch();

if (!$user) {
    echo json_encode(["error" => "User with login \"$requestBody[login]\" doesn't exist"]);
    http_response_code(404);
    $userFindQuery = null;
    $db = null;
    exit();
}

if (password_verify($requestBody["pass"], $user["password"])) {
    $_SESSION["login"] = $user["login"];
    $_SESSION["userId"] = $user["id"];

    echo json_encode(["ok" => true]);
    http_response_code(200);
} else {
    echo json_encode(["error" => "Invalid password"]);
    http_response_code(401);
}

$userFindQuery = null;
$db = null;
