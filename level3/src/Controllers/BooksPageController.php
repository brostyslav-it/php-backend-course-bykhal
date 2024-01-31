<?php

namespace App\Controllers;

use App\Models\BooksModel;
use Core\Controller;

class BooksPageController extends Controller
{
    private const int BOOKS_PER_PAGE = 2;
    private BooksModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new BooksModel();
    }

    public function renderTemplate(): void
    {
        $books = $this->getBooksByParams();
        $offset = (!isset($_GET['offset']) || $_GET['offset'] < 1) ? self::BOOKS_PER_PAGE : $_GET['offset'];

        $this->view('books-page', ['books' => $this->getBooksWithOffset($books, $offset), 'currentOffset' => $offset, 'count' => self::BOOKS_PER_PAGE]);
    }

    private function getBooksByParams(): array
    {
        return match (true) {
            isset($_GET['search']) => $this->model->getBooksByTitle(htmlentities($_GET['search'])),
            isset($_GET['author']) => $this->model->getBooksByAuthorId(htmlentities($_GET['author'])),
            isset($_GET['year']) => $this->model->getBooksByYear(htmlentities($_GET['year'])),
            default => $this->model->getBooks()
        };
    }

    private function getBooksWithOffset(array $allBooks, int $offset): array
    {
        return array_slice($allBooks, 0, $offset, true);
    }
}
