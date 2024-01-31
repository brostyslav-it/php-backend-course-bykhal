<?php $static = "/static/books-page/books-page_files"; ?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Бібліотека</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="library Sh++">
    <link rel="stylesheet" href="<?= $static ?>/libs.min.css">
    <link rel="stylesheet" href="<?= $static ?>/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
          crossorigin="anonymous"/>

    <link rel="shortcut icon" href="http://localhost:3000/favicon.ico">
    <style>
        .details {
            display: none;
        }
    </style>
</head>

<body data-gr-c-s-loaded="true" class="">
<?php $view->component('header'); ?>

<section id="main" class="main-wrapper">
    <div class="container">
        <div id="content" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php foreach ($data['books'] as $book): ?>
                <div data-book-id="<?= $book['id'] ?>" class="book_item col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <div class="book">
                        <a href="/book/<?= $book['id'] ?>"><img
                                    src="<?= '/static/book-images/' . $book['id'] . $book['img'] ?>"
                                    alt="<?= $book['title'] ?>">
                            <div data-title="<?= $book['title'] ?>" class="blockI" style="height: 46px;">
                                <div data-book-title="<?= $book['title'] ?>"
                                     class="title size_text"><?= $book['title'] ?></div>

                                <div data-book-author="" class="author">
                                    <?= implode(', ', $book['authors']) ?>
                                </div>
                            </div>
                        </a>
                        <a href="/book/<?= $book['id'] ?>">
                            <button type="button" class="details btn btn-success">Читать</button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container">
        <nav>
            <ul class="pagination">
                <?php if (isset($_GET['offset'])): ?>
                <li class="page-item">
                    <a class="page-link" href="/?<?php if ($_GET['offset'] <= $data['count']) { unset($_GET['offset']); echo http_build_query($_GET); } else { echo http_build_query(array_merge($_GET, ['offset' => $data['currentOffset'] - $data['count']])); }?>">Назад</a>
                </li>
                <?php endif; ?>

                <li class="page-item">
                    <a class="page-link" href="/?<?= http_build_query(array_merge($_GET, ['offset' => $data['currentOffset'] + $data['count']])) ?>">Вперед</a>
                </li>
            </ul>
        </nav>
    </div>
</section>

<?php $view->component('footer'); ?>

<div class="sweet-overlay" tabindex="-1" style="opacity: -0.02; display: none;"></div>
<div class="sweet-alert hideSweetAlert" data-custom-class="" data-has-cancel-button="false"
     data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop"
     data-timer="null" style="display: none; margin-top: -169px; opacity: -0.03;">
    <div class="sa-icon sa-error" style="display: block;">
            <span class="sa-x-mark">
        <span class="sa-line sa-left"></span>
            <span class="sa-line sa-right"></span>
            </span>
    </div>
    <div class="sa-icon sa-warning" style="display: none;">
        <span class="sa-body"></span>
        <span class="sa-dot"></span>
    </div>
    <div class="sa-icon sa-info" style="display: none;"></div>
    <div class="sa-icon sa-success" style="display: none;">
        <span class="sa-line sa-tip"></span>
        <span class="sa-line sa-long"></span>

        <div class="sa-placeholder"></div>
        <div class="sa-fix"></div>
    </div>
    <div class="sa-icon sa-custom" style="display: none;"></div>
    <h2>Ооопс!</h2>
    <p style="display: block;">Ошибка error</p>
    <fieldset>
        <input type="text" tabindex="3" placeholder="">
        <div class="sa-input-error"></div>
    </fieldset>
    <div class="sa-error-container">
        <div class="icon">!</div>
        <p>Not valid!</p>
    </div>
</div>
</body>
</html>
