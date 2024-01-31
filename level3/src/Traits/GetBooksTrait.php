<?php

namespace App\Traits;

use App\DB\Connection;

trait GetBooksTrait
{
    public function getAllBooks(Connection $db): array
    {
        $books = $db->query(file_get_contents(SQL_SCRIPTS_PATH . '/get_books.sql'))->fetch_all(MYSQLI_ASSOC);

        foreach ($books as $key => $book) {
            $this->findBookAuthors($db, $book['id'], $books[$key]);
        }

        return $books;
    }

    public function findBookAuthors(Connection $db, $bookId, array &$book): void
    {
        $authorsIdentifiers = $db->query(
            file_get_contents(SQL_SCRIPTS_PATH . '/get_book_authors.sql'),
            ['i', [$bookId]]
        )->fetch_all();

        foreach ($authorsIdentifiers as $id) {
            $book['authors'][] = $db->query(
                file_get_contents(SQL_SCRIPTS_PATH . '/find_author_name.sql'),
                ['i', [$id[0]]]
            )->fetch_all()[0][0];
        }
    }
}
