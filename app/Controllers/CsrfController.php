<?php

namespace App\Controllers;

use App\Helpers\CsrfHelper;
use App\Helpers\SessionHelper;

class CsrfController
{
    public function token(): void
    {
        SessionHelper::start();

        $token = \App\Helpers\CsrfHelper::gerarToken();

        header('Content-Type: application/json');
        echo json_encode(['token' => $token]);
    }
}
