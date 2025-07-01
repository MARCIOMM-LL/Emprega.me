<?php
//namespace App\Helpers;
//
//use JetBrains\PhpStorm\NoReturn;
//
//class ResponseHelper
//{
//    public static function json(array $data): void
//    {
//        header('Content-Type: application/json');
//    }
//}

namespace App\Helpers;

class ResponseHelper
{
    public static function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
