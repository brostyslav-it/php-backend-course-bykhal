<?php

namespace App\Models;

use App\DB\Constants;
use App\Traits\GetBooksTrait;
use Core\Model;
use mysqli_result;

/**
 * Class AdminModel
 *
 * Represents the model for administrative operations related to books and authors.
 *
 * @package App\Models
 */
class AdminModel extends Model
{
    use GetBooksTrait;

    /**
     * SQL file name for finding an author.
     */
    private const string FIND_AUTHOR_SQL = 'find_author.sql';

    /**
     * SQL file name for checking admin credentials.
     */
    private const string CHECK_ADMIN_SQL = 'check_admin.sql';

    /**
     * SQL file name for adding an author.
     */
    private const string ADD_AUTHOR_SQL = 'add_author.sql';

    /**
     * SQL file name for getting all authors.
     */
    private const string GET_AUTHORS_SQL = 'get_authors.sql';

    /**
     * SQL file name for adding author relations to a book.
     */
    private const string ADD_BOOK_AUTHOR_SQL = 'add_book_author.sql';

    /**
     * SQL file name for adding a book.
     */
    private const string ADD_BOOK_SQL = 'add_book.sql';

    /**
     * SQL file name for finding a book.
     */
    private const string FIND_BOOK_SQL = 'find_book.sql';

    /**
     * SQL file name for marking a book as deleted.
     */
    private const string MARK_BOOK_DELETED_SQL = 'mark_book_deleted.sql';

    /**
     * Validation rule for checking conditions and updating data array.
     *
     * @param array &$data Reference to the data array to be validated and updated.
     * @param bool $errorIf The condition to check.
     * @param string $errorMessage The error message to be added if validation fails.
     */
    private function validateRule(array &$data, bool $errorIf, string $errorMessage): void
    {
        if ($errorIf) {
            $data['ok'] = false;
            $data['errors'][] = $errorMessage;
        }
    }

    /**
     * Validates multiple rules using the provided data and rules.
     *
     * @param array $data Reference to the data array to be validated and updated.
     * @param array $rules The rules for validation, each containing a condition and an error message.
     *
     * @return array The validation result containing 'ok' and 'errors' keys.
     */
    private function validateRules(array $data, array $rules): array
    {
        for ($i = 0; $i < count($rules); $i++) {
            $this->validateRule($data, $rules[$i][0], $rules[$i][1]);
        }

        return $data;
    }

    /**
     * Validates data based on specified rules.
     *
     * @param array $rules The rules for validation, each containing a condition and an error message.
     *
     * @return array The validation result containing 'ok' and 'errors' keys.
     */
    private function validate(array $rules): array
    {
        return $this->validateRules(self::DEFAULT_DATA, $rules);
    }

    /**
     * Finds an author based on the provided name.
     *
     * @param string $author The name of the author to find.
     *
     * @return false|mysqli_result Result of the author search or false on failure.
     */
    private function findAuthor(string $author): false|mysqli_result
    {
        return $this->query(self::FIND_AUTHOR_SQL, ['s', [$author]]);
    }

    /**
     * Get validation rules for an author.
     *
     * @param string $author The name of the author to be validated.
     *
     * @return array The validation rules containing conditions and error messages.
     */
    private function getAuthorValidationRules(string $author): array
    {
        return empty($author) || mb_strlen($author) > 300
            ? [[true, "Ім'я автора має бути від 1 до 300 символів"]]
            : [[$this->findAuthor($author)->num_rows > 0, "Автор \"$author\" вже існує"]];
    }

    /**
     * Get validation rules for book-related data.
     *
     * @param string|null $title The title of the book.
     * @param array|null $authors The array of authors for the book.
     * @param array|null $img The array containing information about the book image.
     * @param int|null $year The publication year of the book.
     * @param int|null $pages The number of pages in the book.
     * @param string|null $isbn The ISBN of the book.
     * @param string|null $description The description of the book.
     *
     * @return array The validation rules containing conditions and error messages.
     */
    private function getBookValidationRules(?string $title, ?array $authors, ?array $img, ?int $year, ?int $pages, ?string $isbn, ?string $description): array
    {
        return [
            [empty($title) || mb_strlen($title) > 100, "Назва книги має бути від 1 до 100 символів"],
            [empty($authors), "Додайте авторів"],
            [count($authors) !== count(array_unique($authors)), "Автори не можуть повторюватись"],
            [count($authors) > 5, "У книги може бути максимум 5 авторів"],
            [array_reduce($authors, fn ($carry, $author) => $carry || (mb_strlen($author) > 300), false), "Ім'я автора має бути від 1 до 300 символів"],
            [empty($img), "Завантажте зображення"],
            [empty($img['name']) || mb_strlen($img['name']) > 100, "Назва зображення має бути від 1 до 100 символів"],
            [empty($year) || $year > date('Y') || $year < 0, "Введіть правильний рік"],
            [empty($pages) || $pages < 0, "Введіть правильну кількість сторінок"],
            [empty($isbn) || mb_strlen($isbn) > 20, "ISBN книги має бути від 1 до 20 символів"],
            [empty($description), "Введіть опис книги"]
        ];
    }

    /**
     * Checks if the provided login and password combination corresponds to an admin.
     *
     * @param string $login The login of the admin.
     * @param string $password The password of the admin.
     *
     * @return bool True if the admin credentials are correct, false otherwise.
     */
    public function isCorrectAdmin(string $login, string $password): bool
    {
        return $this->query(self::CHECK_ADMIN_SQL, ['ss', [$login, $password]])->num_rows > 0;
    }

    /**
     * Adds an entity to the database and returns the validation result.
     *
     * @param array $data The validation result containing 'ok' and 'errors' keys.
     * @param callable $addFunction The function to execute if validation passes.
     *
     * @return array The validation result containing 'ok' and 'errors' keys.
     */
    private function addEntity(array $data, callable $addFunction): array
    {
        if ($data['ok']) {
            $addFunction();
        }

        return $data;
    }

    /**
     * Adds an author to the database and returns the validation result.
     *
     * @param string $author The name of the author to be added.
     *
     * @return array The validation result containing 'ok' and 'errors' keys.
     */
    public function addAuthor(string $author): array
    {
        return $this->addEntity($this->validate($this->getAuthorValidationRules($author)), function () use ($author) {
            $this->query(self::ADD_AUTHOR_SQL, ['s', [$author]]);
        });
    }

    /**
     * Retrieves all authors from the database.
     *
     * @return array The array containing information about all authors.
     */
    public function getAuthors(): array
    {
        return $this->query(self::GET_AUTHORS_SQL)->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Establishes author relations for a book in the database.
     *
     * @param array $authors The array of authors for the book.
     * @param int $bookId The ID of the book for which author relations are established.
     *
     * @return void
     */
    private function addBookAuthorRelations(array $authors, int $bookId): void
    {
        foreach ($authors as $author) {
            $authorResult = $this->findAuthor($author);

            if ($authorResult->num_rows > 0) {
                $this->query(self::ADD_BOOK_AUTHOR_SQL, ['ii', [$bookId, $authorResult->fetch_assoc()['id']]]);
            }
        }
    }

    /**
     * Adds a book to the database and establishes author relations.
     *
     * @param string|null $title The title of the book.
     * @param array|null $authors The array of authors for the book.
     * @param array|null $img The array containing information about the book image.
     * @param int|null $year The publication year of the book.
     * @param int|null $pages The number of pages in the book.
     * @param string|null $isbn The ISBN of the book.
     * @param string|null $description The description of the book.
     *
     * @return array The validation result containing 'ok' and 'errors' keys.
     */
    public function addBook(?string $title, ?array $authors, ?array $img, ?int $year, ?int $pages, ?string $isbn, ?string $description): array
    {
        print_r($authors);
        return $this->addEntity($this->validate($this->getBookValidationRules($title, $authors, $img, $year, $pages, $isbn, $description)), function () use ($authors, $description, $isbn, $pages, $year, $img, $title) {
            $this->query(self::ADD_BOOK_SQL, ['ssiiss', [$title, $img['name'], $year, $pages, $isbn, $description]]);
            $bookId = $this->db->id();

            $this->addBookAuthorRelations($authors, $bookId);
            move_uploaded_file($img['tmp_name'], Constants::BOOK_IMAGES_PATH . $bookId . basename($img['name']));
        });
    }

    /**
     * Retrieves all books from the database.
     *
     * @return array The array containing information about all books.
     */
    public function getBooks(): array
    {
        return $this->getAllBooks($this->db);
    }

    /**
     * Marks a book as deleted in the database.
     *
     * @param int $bookId The ID of the book to be marked as deleted.
     *
     * @return array The validation result containing 'ok' and 'errors' keys.
     */
    public function markBookDeleted(int $bookId): array
    {
        if ($this->query(self::FIND_BOOK_SQL, ['i', [$bookId]])->num_rows > 0) {
            $this->query(self::MARK_BOOK_DELETED_SQL, ['i', [$bookId]]);
            return self::DEFAULT_DATA;
        }

        return ['ok' => false, 'errors' => ["Книги з ID $bookId не існує"]];
    }
}
