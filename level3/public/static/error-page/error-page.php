<?php
$static = "/static/book-page/book-page_files";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404</title>

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
    <link rel="shortcut icon" href="http://localhost/level3/favicon.ico">
</head>
<body>
<?php $view->component('header'); ?>

<div class="container mt-5 mb-5">
    <h3>Помилка :(</h3>

    <div class="alert alert-danger" role="alert">
        <?php foreach ($data['errors'] as $error): ?>
            <?= $error . '<br>' ?>
        <?php endforeach; ?>
    </div>
</div>

<?php $view->component('footer'); ?>
</body>
</html>
