<?php

namespace App\Models;

use App\Traits\GetBooksTrait;
use Core\Model;

class AdminModel extends Model
{
    use GetBooksTrait;

    public function isCorrectAdmin(string $login, string $password): bool
    {
        $result = $this->db->query(
            file_get_contents(SQL_SCRIPTS_PATH . '/check_admin.sql'),
            ['ss', [$login, $password]]
        );

        return $result->num_rows > 0;
    }

    public function addAuthor(string $author): array
    {
        $data = $this->validateAuthor($author);

        if ($data['ok']) {
            $this->db->query(
                file_get_contents(SQL_SCRIPTS_PATH . '/add_author.sql'),
                ['s', [$author]]
            );
        }

        return $data;
    }

    private function validateAuthor(string $author): array
    {
        $data = ['ok' => true, 'errors' => []];

        if (empty($author) || mb_strlen($author) > 300) {
            $data['ok'] = false;
            $data['errors'][] = "Ім'я автора має бути від 1 до 300 символів";
        } else if ($this->db->query(file_get_contents(SQL_SCRIPTS_PATH . '/find_author.sql'), ['s', [$author]])->num_rows !== 0) {
            $data['ok'] = false;
            $data['errors'][] = "Автор \"$author\" вже існує";
        }

        return $data;
    }

    public function getAuthors(): array
    {
        return $this->db->query(file_get_contents(SQL_SCRIPTS_PATH . '/get_authors.sql'))->fetch_all(MYSQLI_ASSOC);
    }

    public function addBook(?string $title, ?array $authors, ?array $img, ?int $year, ?int $pages, ?string $isbn, ?string $description): array
    {
        $data = $this->validateBook($title, $authors, $img, $year, $pages, $isbn, $description);

        if ($data['ok']) {
            $this->db->query(
                file_get_contents(SQL_SCRIPTS_PATH . '/add_book.sql'),
                ['ssiiss', [$title, $img['name'], $year, $pages, $isbn, $description]]
            );

            $bookId = $this->db->id();

            foreach ($authors as $author) {
                $authorResult = $this->db->query(
                    file_get_contents(SQL_SCRIPTS_PATH . '/find_author.sql'),
                    ['s', [$author]]
                );

                if ($authorResult->num_rows > 0) {
                    $this->db->query(
                        file_get_contents(SQL_SCRIPTS_PATH . '/add_book_author.sql'),
                        ['ii', [$bookId, $authorResult->fetch_assoc()['id']]]
                    );
                }
            }

            move_uploaded_file($img['tmp_name'], BOOK_IMAGES_PATH . $bookId . basename($img['name']));
        }

        return $data;
    }

    private function validateBook(?string $title, ?array $authors, ?array $img, ?int $year, ?int $pages, ?string $isbn, ?string $description): array
    {
        $data = ['ok' => true, 'errors' => []];

        if (empty($title) || mb_strlen($title) > 100) {
            $data['ok'] = false;
            $data['errors'][] = "Назва книги має бути від 1 до 100 символів";
        }

        if (empty($authors)) {
            $data['ok'] = false;
            $data['errors'][] = "Додайте авторів";
        } else if (count($authors) > 5) {
            $data['ok'] = false;
            $data['errors'][] = "У книги може бути максимум 5 авторів";
        } else {
            if (count($authors) !== count(array_unique($authors))) {
                $data['ok'] = false;
                $data['errors'][] = "Автори не можуть повторюватись";
            }

            foreach ($authors as $author) {
                if (empty($author) || mb_strlen($author) > 300) {
                    $data['ok'] = false;
                    $data['errors'][] = "Ім'я автора має бути від 1 до 300 символів";
                }
            }
        }

        if (empty($img) || !file_exists($img['tmp_name'])) {
            $data['ok'] = false;
            $data['errors'][] = "Завантажте зображення";
        } else if (mb_strlen($img['name']) > 100) {
            $data['ok'] = false;
            $data['errors'][] = "Назва зображення має бути не більше 100 символів";
        }

        if (empty($year)) {
            $data['ok'] = false;
            $data['errors'][] = "Введіть рік";
        }

        if ($year > date('Y') || $year < 0) {
            $data['ok'] = false;
            $data['errors'][] = "Рік видання не може бути більшим за поточний рік або менше 0";
        }

        if (empty($pages)) {
            $data['ok'] = false;
            $data['errors'][] = "Введіть кількість сторінок";
        }

        if ($pages < 0) {
            $data['ok'] = false;
            $data['errors'][] = "Кількість сторінок не може бути менше 0";
        }

        if (empty($isbn) || mb_strlen($isbn) > 20) {
            $data['ok'] = false;
            $data['errors'][] = "ISBN книги має бути від 1 до 20 символів";
        }

        if (empty($description)) {
            $data['ok'] = false;
            $data['errors'][] = "Введіть опис книги";
        }

        return $data;
    }

    public function getBooks(): array
    {
        return $this->getAllBooks($this->db);
    }

    public function deleteBook(int $id)
    {

    }
}
