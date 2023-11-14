<?php
const HOST = "localhost";
const DB_NAME = "todo_items";
const TABLE_NAME = "items";
const USER = "root";
const PASSWORD = "";

$mysqli = new mysqli(HOST, USER, PASSWORD, DB_NAME);
$mysqli->query("CREATE TABLE IF NOT EXISTS " . ITEMS_TABLE_NAME
    . "(id INT AUTO_INCREMENT NOT NULL PRIMARY KEY, text TEXT NOT NULL, checked BOOL DEFAULT FALSE)");