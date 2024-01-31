<?php

namespace App\DB;

class Connection
{
    private static ?Connection $instance = null;
    private \mysqli $connection;

    private string $host = 'localhost';
    private string $username = 'root';
    private string $password = 'Rostik2005$';

    private function __construct()
    {
        $this->connection = new \mysqli($this->host, $this->username, $this->password);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }

        $this->createDB();
    }

    private function createDB(): void
    {
        $this->connection->multi_query(file_get_contents(__DIR__ . '/sql_scripts/create_db.sql'));
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();

            $migrations = new Migrations();
            $migrations->makeMigrations();
        }

        return self::$instance;
    }

    public function query(string $sql, array $params = null): false|\mysqli_result
    {
        $this->resetResults();

        $query = $this->connection->prepare($sql);

        if ($params !== null) {
            $query->bind_param($params[0], ...$params[1]);
        }

        $query->execute();

        return $query->get_result();
    }

    public function id(): int|string
    {
        return $this->connection->insert_id;
    }

    private function resetResults(): void
    {
        while ($this->connection->next_result()) {
            $this->connection->store_result();
        }
    }

    public function __clone() {}
    public function __wakeup() {}
}
