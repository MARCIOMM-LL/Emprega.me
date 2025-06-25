<?php
namespace App\Core;

class Controller {
    protected function view($view, $data = []): void
    {
        extract($data);
        require __DIR__ . "/../Views/{$view}.php";
    }
}