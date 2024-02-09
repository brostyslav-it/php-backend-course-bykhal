<?php

namespace App\DB;

interface Constants
{
    const string SQL_SCRIPTS_PATH = __DIR__ . '/sql_scripts';
    const string MIGRATIONS_PATH = __DIR__ . '/migrations';
    const string BOOK_IMAGES_PATH = STATIC_PATH . '/book-images/';
}
