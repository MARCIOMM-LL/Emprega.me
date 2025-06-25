<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AutocompleteController;

$url = $_SERVER['REQUEST_URI'];

if (strpos($url, '/api/autocomplete/pesquisar') !== false) {
    (new AutocompleteController())->pesquisar();
    exit;
}
