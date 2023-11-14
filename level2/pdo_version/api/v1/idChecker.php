<?php
require_once "connection.php";

function isIdExists($id): bool {
    global $db;
    $checkQuery = $db->prepare("SELECT * FROM items WHERE id = ?");
    $checkQuery->execute([$id]);

    return $checkQuery->rowCount() > 0;
}
