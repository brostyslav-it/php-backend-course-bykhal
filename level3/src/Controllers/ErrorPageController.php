<?php

namespace App\Controllers;

use core\Controller;

class ErrorPageController extends Controller
{
    public function renderTemplate(array $errors): void
    {
        $this->view('error-page', ['errors' => $errors]);
    }
}
