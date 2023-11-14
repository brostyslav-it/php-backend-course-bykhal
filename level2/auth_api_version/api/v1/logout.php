<?php
session_start();
session_destroy();

require_once "loadHeaders.php";
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

echo json_encode(["ok" => true]);