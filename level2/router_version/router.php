<?php
header("Access-Control-Allow-Origin: https://frontend.com");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Credentials: true');
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

session_set_cookie_params([
    'samesite' => 'None',
    'secure' => true,
]);
session_start();

require_once "connection.php";

const MAX_LOGIN_LENGTH = 30;
const MAX_PASS_LENGTH = 50;
const COUNTER_FILE_PATH = "idCounter.txt";

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    http_response_code(200);
    exit();
}

$db = getDatabaseConnection();
$requestBody = json_decode(file_get_contents('php://input'), true);

switch ($_GET["action"]) {
    case "login":
        loginUser($requestBody, $db);
        break;

    case "logout":
        logoutUser();
        break;

    case "register":
        registerUser($requestBody, $db);
        break;

    case "getItems":
        getItems($db);
        break;

    case "addItem":
        addItem($requestBody, $db);
        break;

    case "deleteItem":
        deleteItem($requestBody, $db);
        break;

    case "changeItem":
        changeItem($requestBody, $db);
        break;
}

function checkLoginData(array $requestBody): void
{
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        http_response_code(405);
        echo json_encode(["error" => "Only POST method allowed"]);
        exit();
    }

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
}

function loginUser(array $requestBody, $db): void
{
    checkLoginData($requestBody);

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
}

function logoutUser(): void
{
    session_destroy();
    echo json_encode(["ok" => true]);
}

function checkRegisterData(array $requestBody): void
{
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        http_response_code(405);
        echo json_encode(["error" => "Only POST method allowed"]);
        exit();
    }

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
}

function isUsernameAvailable(string $username, $db): bool
{
    $findUsernameQuery = $db->prepare("SELECT * FROM users WHERE login = ?");
    $findUsernameQuery->execute([$username]);

    return $findUsernameQuery->rowCount() === 0;
}

function registerUser(array $requestBody, $db): void
{
    checkRegisterData($requestBody);

    if (!isUsernameAvailable($requestBody["login"], $db)) {
        http_response_code(400);
        echo json_encode(["error" => "User with login \"$requestBody[login]\" already exists!"]);
        exit();
    }

    $addUserQuery = $db->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
    $addUserQuery->execute([$requestBody["login"], password_hash($requestBody["pass"], PASSWORD_DEFAULT)]);

    http_response_code(201);
    echo json_encode(["ok" => true]);
}

function checkGetItemsData(): void
{
    if (!isset($_SESSION["userId"])) {
        http_response_code(401);
        echo json_encode(["error" => "You must log in to get items"]);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] != "GET") {
        http_response_code(405);
        echo json_encode(["error" => "Only GET method allowed"]);
        exit();
    }
}

function getItems($db): void
{
    checkGetItemsData();

    $items = $db->prepare("SELECT * FROM items WHERE userId = ?");
    $items->execute([$_SESSION["userId"]]);

    echo json_encode(["items" => $items->fetchAll(PDO::FETCH_ASSOC)]);
}

function checkAddData(array $requestBody): void
{
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        http_response_code(405);
        echo json_encode(["error" => "Only POST method allowed"]);
        exit();
    }

    if (!isset($_SESSION["userId"])) {
        http_response_code(401);
        echo json_encode(["error" => "You must log in to add items"]);
        exit();
    }

    if (!isset($requestBody["text"]) or empty($requestBody["text"])) {
        http_response_code(400);
        echo json_encode(["error" => "No data given"]);
        exit();
    }
}

function addItem(array $requestBody, $db): void
{
    checkAddData($requestBody);

    $addItemQuery = $db->prepare("INSERT INTO items (text, userId) VALUES (?, ?)");
    $addItemQuery->execute([$requestBody["text"], $_SESSION["userId"]]);

    http_response_code(201);
    echo json_encode(["id" => $db->lastInsertId()]);
}

function checkDeleteData(array $requestBody): void
{
    if ($_SERVER["REQUEST_METHOD"] != "DELETE") {
        http_response_code(405);
        echo json_encode(["error" => "Only DELETE method allowed"]);
        exit();
    }

    if (!isset($_SESSION["userId"])) {
        http_response_code(401);
        echo json_encode(["error" => "You must log in to delete items"]);
        exit();
    }

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
}

function isIdExists($id, $userId, $db): bool {
    $checkQuery = $db->prepare("SELECT * FROM items WHERE id = ? AND userId = ?");
    $checkQuery->execute([$id, $userId]);

    return $checkQuery->rowCount() > 0;
}

function deleteItem(array $requestBody, $db): void
{
    checkDeleteData($requestBody);

    if (!isIdExists($requestBody["id"], $_SESSION["userId"], $db)) {
        http_response_code(400);
        echo json_encode(["error" => "ID doesn't match for current user"]);
    } else {
        $deleteQuery = $db->prepare("DELETE FROM items WHERE id = ?");
        $deleteQuery->execute([$requestBody["id"]]);

        $updateId = $db->prepare("UPDATE items SET id = id - 1 WHERE id > ?");
        $updateId->execute([$requestBody["id"]]);
        $db->query("ALTER TABLE items AUTO_INCREMENT = 1");

        echo json_encode(["ok" => true]);
    }
}

function checkChangeData(array $requestBody): void
{
    if ($_SERVER["REQUEST_METHOD"] != "PUT") {
        http_response_code(405);
        echo json_encode(["error" => "Only PUT method allowed"]);
        exit();
    }

    if (!isset($_SESSION["userId"])) {
        http_response_code(401);
        echo json_encode(["error" => "You must log in to change items"]);
        exit();
    }

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
}

function changeItem(array $requestBody, $db): void
{
    checkChangeData($requestBody);

    if (!isIdExists($requestBody["id"], $_SESSION["userId"], $db)) {
        http_response_code(400);
        echo json_encode(["error" => "Element id doesn't match for current user"]);
    } else {
        $changeQuery = $db->prepare("UPDATE items SET text = ?, checked = ? WHERE id = ?");
        $changeQuery->execute([
            $requestBody["text"],
            (int) $requestBody["checked"],
            $requestBody["id"]
        ]);

        echo json_encode(["ok" => true]);
    }
}
