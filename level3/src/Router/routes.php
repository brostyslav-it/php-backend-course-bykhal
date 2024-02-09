<?php

namespace App\Router;

use App\Controllers\AdminController;
use App\Controllers\BookPageController;
use App\Controllers\BooksPageController;

return [
    Route::get('#/#', [BooksPageController::class, 'renderTemplate']),
    Route::get('#/book/\d+#', [BookPageController::class, 'renderTemplate']),
    Route::get('#/admin#', [AdminController::class, 'renderTemplate']),
    Route::get('#/admin/api/v1/page/\d+#', [AdminController::class, 'page']),
    Route::post('#/admin/api/v1/addAuthor#', [AdminController::class, 'addAuthor']),
    Route::post('#/admin/api/v1/addBook#', [AdminController::class, 'addBook']),
    Route::post('#/admin/api/v1/\d+/deleteBook#', [AdminController::class, 'deleteBook']),
    Route::post('#/api/v1/book/\d+/want#', [BookPageController::class, 'incrementWantsCounter']),
];
