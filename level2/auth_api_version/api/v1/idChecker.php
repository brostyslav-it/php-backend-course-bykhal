<?php
require_once "connection.php";

function isIdExists($id, $userId): bool {
    global $db;
    $checkQuery = $db->prepare("SELECT * FROM items WHERE id = ? AND userId = ?");
    $checkQuery->execute([$id, $userId]);

    return $checkQuery->rowCount() > 0;
}
