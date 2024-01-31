<?php

namespace App\Models;

use App\Traits\GetBooksTrait;
use Core\Model;

class BooksModel extends Model
{
    use GetBooksTrait;

    public function getBooks(): array
    {
        return $this->getAllBooks($this->db);
    }

    public function getBooksByTitle(string $title): array
    {
        $books = $this->db->query(
            file_get_contents(SQL_SCRIPTS_PATH . '/get_book_by_title.sql'),
            ['s', ["%$title%"]]
        )->fetch_all(MYSQLI_ASSOC);

        foreach ($books as $key => $book) {
            $this->findBookAuthors($this->db, $book['id'], $books[$key]);
        }

        return $books;
    }

    public function getBooksByAuthorId(int $authorId): array
    {
        $booksIdentifiers = $this->db->query(
            file_get_contents(SQL_SCRIPTS_PATH . '/get_books_by_author_id.sql'),
            ['i', [$authorId]]
        )->fetch_all();

        $books = $this->db->query(
            str_replace(':idList', implode(',', array_map('current', $booksIdentifiers)), file_get_contents(SQL_SCRIPTS_PATH . '/get_books_by_identifiers.sql'))
        )->fetch_all(MYSQLI_ASSOC);

        foreach ($books as $key => $book) {
            $this->findBookAuthors($this->db, $book['id'], $books[$key]);
        }

        return $books;
    }

    public function getBooksByYear(int $year): array
    {
        $books = $this->db->query(
            file_get_contents(SQL_SCRIPTS_PATH . '/get_books_by_year.sql'),
            ['i', [$year]]
        )->fetch_all(MYSQLI_ASSOC);

        foreach ($books as $key => $book) {
            $this->findBookAuthors($this->db, $book['id'], $books[$key]);
        }

        return $books;
    }
}
