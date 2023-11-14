<?php
const HOST = "localhost";
const DB_NAME = "todo_items";
const ITEMS_TABLE_NAME = "items";
const USERS_TABLE_NAME = "users";
const USER = "root";
const PASSWORD = "";

function getDatabaseConnection()
{
    try {
        $db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASSWORD);
        $db->query("CREATE TABLE IF NOT EXISTS " . USERS_TABLE_NAME
            . "(id INT AUTO_INCREMENT NOT NULL PRIMARY KEY, login CHAR(30) UNIQUE NOT NULL,
    password TEXT NOT NULL)");
        $db->query("CREATE TABLE IF NOT EXISTS " . ITEMS_TABLE_NAME
            . "(id INT AUTO_INCREMENT NOT NULL PRIMARY KEY, text 
        TEXT NOT NULL, checked BOOL DEFAULT FALSE, userId INT NOT NULL, 
        FOREIGN KEY (userId) REFERENCES " . USERS_TABLE_NAME . "(id))");

        return $db;
    } catch (PDOException $exception) {
        http_response_code(500);
        echo json_encode(["error" => $exception->getMessage()]);
    }
}
