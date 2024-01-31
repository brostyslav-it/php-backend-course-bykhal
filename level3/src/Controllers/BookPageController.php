<?php

namespace App\Controllers;

use App\Models\BookModel;
use Core\Controller;

class BookPageController extends Controller
{
    private BookModel $model;
    private int $id;

    public function __construct()
    {
        parent::__construct();
        $this->model = new BookModel();
        $this->id = $this->extractId()[0][0];
    }

    public function renderTemplate(): void
    {
        if ($this->model->getBook($this->id)) {
            $this->model->incrementViewsCounter($this->id);
            $book = $this->model->getBook($this->id);
            $this->view('book-page', ['book' => $book]);
        } else {
            echo "There is no book :(";
        }
    }

    public function incrementWantsCounter(): void
    {
        echo json_encode($this->model->incrementWantsCounter($this->extractId()[0][1]));
    }

    private function extractId(): array
    {
        preg_match_all('#\d+#', $_SERVER['REQUEST_URI'], $book_id);
        return $book_id;
    }
}
