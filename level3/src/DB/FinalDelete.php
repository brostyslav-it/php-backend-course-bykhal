<?php

namespace App\DB;

class FinalDelete
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    private function getSoftDeletedBooks(): array
    {
        return $this->db->query(file_get_contents(Constants::SQL_SCRIPTS_PATH . '/find_soft_deleted_books.sql'))->fetch_all(MYSQLI_ASSOC);
    }

    private function deleteBooks(): void
    {
        $this->db->query(file_get_contents(Constants::SQL_SCRIPTS_PATH . '/delete_book.sql'));
    }

    private function deleteBooksAuthorsRelations(array $softDeletedBooks): void
    {
        foreach ($softDeletedBooks as $softDeletedBook) {
            $this->db->query(
                file_get_contents(Constants::SQL_SCRIPTS_PATH . '/delete_book_author_relation.sql'),
                ['i', [$softDeletedBook['id']]]
            );
        }
    }

    private function deleteBooksImages(array $softDeletedBooks): void
    {
        foreach ($softDeletedBooks as $softDeletedBook) {
            unlink(Constants::BOOK_IMAGES_PATH . "$softDeletedBook[id]$softDeletedBook[img]");
        }
    }

    public function finalDelete(): void
    {
        $softDeletedBooks = $this->getSoftDeletedBooks();

        $this->deleteBooks();
        $this->deleteBooksAuthorsRelations($softDeletedBooks);
        $this->deleteBooksImages($softDeletedBooks);
    }
}
