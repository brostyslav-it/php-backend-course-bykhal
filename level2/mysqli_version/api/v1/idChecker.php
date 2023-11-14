<?php
require_once "connection.php";

function isIdExists($id): bool {
    global $mysqli;
    $checkQuery = $mysqli->prepare("SELECT * FROM items WHERE id = ?");
    $checkQuery->execute([$id]);

    return $checkQuery->get_result()->num_rows > 0;
}
