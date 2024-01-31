<?php

namespace App\DB;

readonly class Migrations
{
    private Connection $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    public function makeMigrations(): void
    {
        $this->createMigrationsHistory();
        $this->migrations($this->getMigrationsFiles());
    }

    private function migrations(array $migrationFiles): void
    {
        foreach ($migrationFiles as $migration) {
            if (!$this->isMigrationCompleted($migration)) {
                $this->completeMigration($migration);
                $this->insertMigration($migration);
            }
        }
    }

    private function isMigrationCompleted(string $migration): bool
    {
        return $this->db->query(
            file_get_contents(SQL_SCRIPTS_PATH . "/find_migration.sql"),
            ['s', [$migration]]
        )->num_rows !== 0;
    }

    private function completeMigration(string $migration): void
    {
        $this->db->query(file_get_contents(MIGRATIONS_PATH . "/$migration"));
    }

    private function insertMigration(string $migration): void
    {
        $this->db->query(
            file_get_contents(SQL_SCRIPTS_PATH . "/add_migration.sql"),
            ['s', [$migration]]
        );
    }

    private function createMigrationsHistory(): void
    {
        $this->db->query(file_get_contents(SQL_SCRIPTS_PATH . "/create_migrations_history.sql"));
    }

    private function getMigrationsFiles(): array
    {
        return array_diff(scandir(MIGRATIONS_PATH), ['.', '..']);
    }
}
