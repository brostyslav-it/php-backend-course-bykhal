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
        [$books, $param] = $this->getBooksByParams();
        $offset = (!isset($_GET['offset']) || $_GET['offset'] < 1) ? self::BOOKS_PER_PAGE : $_GET['offset'];

        if ($offset > count($books)) {
            $offset = count($books);
            header("Location: /?offset=$offset");
        }

        $data = [
            'books' => $this->getBooksWithOffset($books, $offset),
            'currentOffset' => $offset,
            'count' => self::BOOKS_PER_PAGE
        ];

        if ($param !== null) {
            $data['searchResult'] = "Результати пошуку \"" . htmlspecialchars($_GET[$param]) ."\"";
        }

        $this->view('books-page', $data);
    }

    private function getBooksByParams(): array
    {
        return match (true) {
            isset($_GET['search']) => [$this->model->getBooksByTitle(htmlspecialchars(htmlentities($_GET['search']))), 'search'],
            isset($_GET['author']) => [$this->model->getBooksByAuthorId(htmlspecialchars(htmlentities($_GET['author']))), 'author'],
            isset($_GET['year']) => [$this->model->getBooksByYear(htmlspecialchars(htmlentities($_GET['year']))), 'year'],
            default => [$this->model->getBooks(), null]
        };
    }

    private function getBooksWithOffset(array $allBooks, int $offset): array
    {
        return array_slice($allBooks, 0, $offset, true);
    }
}
