<?php
$static = "/static/book-page/book-page_files";
$book = $data['book'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $book['title'] ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="library Sh++">

    <link rel="stylesheet" href="<?= $static ?>/libs.min.css">
    <link rel="stylesheet" href="<?= $static ?>/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
          crossorigin="anonymous"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body data-gr-c-s-loaded="true" class="">
<div id="want-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Чудово!</h5>
            </div>

            <div class="modal-body">
                <p>Ви можете отримати книгу!</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#want-modal').hide()">Close</button>
            </div>
        </div>
    </div>
</div>
<?php $view->component('header'); ?>

<section id="main" class="main-wrapper">
    <div class="container">
        <div id="content" class="book_block col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="id" book-id="<?= $book['id'] ?>">
                <div id="bookImg" class="col-xs-12 col-sm-3 col-md-3 item" style="margin:;">
                    <img src="<?= '/static/book-images/' . $book['id'] . $book['img']?>" alt="Responsive image" class="img-responsive">
                    <hr>
                </div>

                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 info">
                    <div class="bookInfo col-md-12">
                        <div id="title" class="titleBook"><?= $book['title'] ?></div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="bookLastInfo">
                            <div class="bookRow">
                                <span class="properties">автор:</span>
                                <span id="author"><?= implode(', ', $book['authors']) ?></span>
                            </div>

                            <div class="bookRow">
                                <span class="properties">год:</span>
                                <span id="year"><?= $book['year'] ?></span>
                            </div>

                            <div class="bookRow">
                                <span class="properties">страниц:</span>
                                <span id="pages"><?= $book['pages'] ?></span>
                            </div>

                            <div class="bookRow">
                                <span class="properties">isbn:</span>
                                <span id="isbn"><?= $book['isbn'] ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="btnBlock col-xs-12 col-sm-12 col-md-12">
                        <button type="button" onclick="incrementWantsCounter()" class="btnBookID btn-lg btn btn-success">
                            Хочу читать!
                        </button>
                    </div>

                    <div class="bookDescription col-xs-12 col-sm-12 col-md-12 hidden-xs hidden-sm">
                        <h4>О книге</h4>
                        <hr>
                        <p id="description"><?= $book['description'] ?></p>
                    </div>
                </div>

                <div class="bookDescription col-xs-12 col-sm-12 col-md-12 hidden-md hidden-lg">
                    <h4>О книге</h4>
                    <hr>
                    <p class="description"><?= $book['description'] ?></p>
                </div>

                <div class="container">
                    <div class="bookRow"><span class="properties">Посмотрели:</span><span id="author"> <?= $book['viewsCounter'] ?> раз</span></div>
                    <div class="bookRow"><span class="properties">Захотели:</span><span id="wants"> <?= $book['wantsCounter'] ?></span> раз</div>
                </div>
            </div>

            <script src="<?= $static ?>/book.js" defer=""></script>
        </div>
    </div>
</section>

<?php $view->component('footer'); ?>

<div class="sweet-overlay" tabindex="-1" style="opacity: -0.04; display: none;"></div>
<div class="sweet-alert hideSweetAlert" data-custom-class="" data-has-cancel-button="false"
     data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop"
     data-timer="null" style="display: none; margin-top: -169px; opacity: -0.04;">
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
    <div class="sa-button-container">
        <button class="cancel" tabindex="2" style="display: none; box-shadow: none;">Cancel</button>
        <div class="sa-confirm-button-container">
            <button class="confirm" tabindex="1"
                    style="display: inline-block; background-color: rgb(140, 212, 245); box-shadow: rgba(140, 212, 245, 0.8) 0px 0px 2px, rgba(0, 0, 0, 0.05) 0px 0px 0px 1px inset;">
                OK
            </button>
            <div class="la-ball-fall">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</div>
<script>
    function incrementWantsCounter() {
        $.ajax({
            type: 'POST',
            url: 'http://level3-lib.com/api/v1/book/<?= $book["id"] ?>/want',
            dataType: "json",
            encode: true
        }).done((data) => {
            console.log(data)
            $('#wants').html(` ${data.wantsCounter}`)
            $('#want-modal').show()
        })
    }
</script>
</body>
</html>
