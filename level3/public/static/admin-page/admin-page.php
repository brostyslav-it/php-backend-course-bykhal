<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Адмінка</title>
</head>
<body>

<nav class="navbar bg-dark navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand">Бібліотека++</a>
        <button class="btn btn-outline-light" id="logout-btn">Вийти</button>
    </div>
</nav>

<div class="container mt-3">
    <h2>Додати автора</h2>

    <form class="bg-dark text-light container-sm p-3" id="author-form" method="post" action="/admin/api/v1/addAuthor">
        <div id="author-errors" class="alert alert-danger" style="display: none;"></div>

        <div class="mb-3">
            <label for="author" class="form-label">Автор</label>
            <input type="text" class="form-control" id="author" name="author">
        </div>

        <button class="btn btn-primary" type="submit">Додати автора</button>
    </form>
</div>

<div class="container mt-3">
    <h2>Додати книгу</h2>

    <form class="bg-dark text-light container-sm p-3" id="book-form" method="post" action="/admin/api/v1/addBook" enctype="multipart/form-data">
        <div id="book-errors" class="alert alert-danger" style="display: none;"></div>

        <div class="mb-3">
            <label for="book-name" class="form-label">Назва книги</label>
            <input type="text" class="form-control" id="book-name" name="book_name">
        </div>

        <div class="mb-3">
            <label for="print-year" class="form-label">Рік видання</label>
            <input type="number" class="form-control" id="print-year" name="book_year">
        </div>

        <div class="mb-3">
            <label for="pages" class="form-label">Кількість сторінок</label>
            <input type="number" class="form-control" id="pages" name="book_pages">
        </div>

        <div class="mb-3">
            <label for="book-isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" id="book-isbn" name="book_isbn">
        </div>

        <div class="row d-flex align-items-end">
            <div class="col mb-3">
                <label for="book-image" class="form-label">Додайте зображення книги</label>
                <input class="form-control" type="file" id="book-image" accept="image/png, image/jpeg" name="book_image">
            </div>

            <div class="col mb-3 align-items-end">
                <label for="image-preview" class="form-label">Прев'ю зображення</label>
                <img src="/static/books-images/default.png" width="100" alt="Прев'ю" id="image-preview"
                     class="img-thumbnail">
            </div>
        </div>

        <div class="mb-3">
            <div class="mb-3" id="author-selects">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="mb-3">
                        <label for="author-<?= $i ?>" class="form-label">Автор <?= $i ?></label>

                        <select class="form-control author-select" id="author-<?= $i ?>" name="authors[]">
                            <?php foreach ($data['authors'] as $author): ?>
                                <option value="<?= $author['author'] ?>"><?= $author['author'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="book-description" class="form-label">Опис книги</label>
            <textarea class="form-control" id="book-description" rows="3" name="book_description"></textarea>
        </div>

        <button class="btn btn-primary" type="submit">Додати книгу</button>
    </form>
</div>

<div class="container mt-3">
    <h2>Список книг</h2>

    <table class="table table-dark table-hover table-sm container-sm">
        <thead>
        <tr>
            <th scope="col">Назва книги</th>
            <th scope="col">Автори</th>
            <th scope="col">Рік</th>
            <th scope="col">Дії</th>
            <th scope="col">Кліків</th>
        </tr>
        </thead>
        <tbody class="table-group-divider" id="books-table-body">
        <?php foreach ($data['books'] as $book): ?>
            <tr>
                <th scope="row">
                    <img src="/static/book-images/<?= $book['id'] . $book['img'] ?>" alt="Book" width="100">
                    <?= $book['title'] ?>
                </th>
                <td><?= implode(', ', $book['authors']) ?></td>
                <td><?= $book['year'] ?></td>
                <td>
                    <form method="post" action="/admin/api/v1/<?= $book['id'] ?>/deleteBook" onsubmit="return confirm('Впевнені у видаленні?');">
                        <button class="btn btn-light" type="submit">Видалити</button>
                    </form>
                </td>
                <td><?= $book['viewsCounter'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<nav aria-label="...">
    <ul class="pagination pagination-lg justify-content-center">
        <?php for ($i = 0; $i < $data['pages']; $i++): ?>
        <li class="page-item"><a class="page-link" href="/admin/<?= $i + 1 ?>"><?= $i + 1 ?></a></li>
        <?php endfor; ?>
    </ul>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
<script>
    document.getElementById('book-image').onchange = () => {
        const preview = document.getElementById('image-preview')
        const file = document.getElementById('book-image').files[0]
        const reader = new FileReader()

        reader.onload = function () {
            preview.src = reader.result
        }

        if (file) {
            reader.readAsDataURL(file)
        }
    }

    document.getElementById('logout-btn').onclick = (e) => {
        e.preventDefault()
        window.location.assign('http://level3-lib.com/')

        fetch('http://level3-lib.com/admin/api/v1/logout', {
            credentials: 'include',
            headers: {
                'Authorization': 'Basic ' + btoa('logout:logout'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
    }
</script>
</body>
</html>
