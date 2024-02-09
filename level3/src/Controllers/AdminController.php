<?php

namespace App\Controllers;

use App\Models\AdminModel;
use Core\Controller;

class AdminController extends Controller
{
    private AdminModel $model;
    private ErrorPageController $error;
    private const int BOOKS_PER_PAGE = 2;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminModel();
        $this->error = new ErrorPageController();
        $this->basicAuth();
    }

    private function basicAuth(): void
    {
        if ($this->isUnAuthorized() || !$this->model->isCorrectAdmin($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
            header('WWW-Authenticate: Basic realm="Увійдіть в акаунт"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
    }

    private function isUnAuthorized(): bool
    {
        return !isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] === 'logout';
    }

    public function renderTemplate(): void
    {
        $allBooks = $this->getBooks();
        $pages = ceil(count($allBooks) / self::BOOKS_PER_PAGE);
        $pageNumber = $this->getPageNumber() > $pages ? $pages : ($this->getPageNumber() < 1 ? 1 : $this->getPageNumber());

        $books = $this->getBooksForPage($allBooks, $pageNumber);

        $this->view(
            'admin-page',
            [
                'authors' => $this->getAuthors(),
                'books' => $books,
                'pages' => $pages
            ]
        );
    }

    public function addAuthor(): void
    {
        $this->handleAddErrors($this->model->addAuthor(trim(htmlentities($_POST['author']))));
    }

    public function getAuthors(): array
    {
        return $this->model->getAuthors();
    }

    public function addBook(): void
    {
        $this->handleAddErrors($this->model->addBook(
            $this->handleArgument($_POST['book_name']),
            array_values(array_filter($_POST['authors'], fn ($el) => $el !== '')),
            $this->handleArgument($_FILES['book_image']),
            $this->handleArgument($_POST['book_year']),
            $this->handleArgument($_POST['book_pages']),
            $this->handleArgument($_POST['book_isbn']),
            $this->handleArgument($_POST['book_description'])
        ));
    }

    private function getBooks(): array
    {
        return $this->model->getBooks();
    }

    private function getBooksForPage(array $books, int $page): array
    {
        return array_slice($books, ($page - 1) * self::BOOKS_PER_PAGE, self::BOOKS_PER_PAGE);
    }

    public function deleteBook(): void
    {
        preg_match_all('#\d+#', $_SERVER['REQUEST_URI'], $book_id);
        $this->handleAddErrors($this->model->markBookDeleted($book_id[0][1]));
    }

    private function handleAddErrors(array $result): void
    {
        if ($result['ok']) {
            header('Location: /admin');
        } else {
            $this->error->renderTemplate($result['errors']);
        }
    }

    private function handleArgument(mixed $argument): ?string
    {
        return empty($argument) ? null : htmlspecialchars($argument);
    }

    private function getPageNumber(): int
    {
        preg_match('#\d+#', $_SERVER['REQUEST_URI'], $page);
        return $page[0] ?? 1;
    }
}
