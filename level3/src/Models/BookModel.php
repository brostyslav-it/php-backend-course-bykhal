<?php

namespace App\Models;

use App\Traits\GetBooksTrait;
use Core\Model;

class BookModel extends Model
{
    use GetBooksTrait;

    public function getBook(int $id): array|false
    {
        $result = $this->query('find_book.sql', ['i', [$id]]);

        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();
            $this->findBookAuthors($this->db, $id, $book);

            return $book;
        }

        return false;
    }

    public function incrementViewsCounter(int $id): void
    {
        $this->query('increment_book_views.sql', ['i', [$id]]);
    }

    public function incrementWantsCounter(int $id): array
    {
        $this->query('increment_wants_counter.sql', ['i', [$id]]);

        return $this->query('get_wants_counter.sql', ['i', [$id]])->fetch_assoc();
    }
}
