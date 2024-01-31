<?php

namespace App\View;

class View
{
    public function page(string $name, array $data): void
    {
        $viewPath = STATIC_PATH . "/$name/$name.php";

        if (!file_exists($viewPath)) {
            echo "Error, not found!";
        }

        extract([
            'data' => $data,
            'view' => $this
        ]);

        require_once $viewPath;
    }

    public function component(string $name): void
    {
        $componentPath = STATIC_PATH . "/components/$name.php";

        if (!file_exists($componentPath)) {
            echo "Error, not found!";
        }

        require_once $componentPath;
    }
}
