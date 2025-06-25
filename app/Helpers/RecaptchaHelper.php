<?php
namespace App\Helpers;

class RecaptchaHelper
{
    private static string $secretKey = '6LcExmgrAAAAAFXSHPg0K6GF09hOprV4hLMOsXUE'; // ⚠️ A tua chave secreta aqui

    public static function validar(string $recaptchaResponse): bool
    {
        if (empty($recaptchaResponse)) {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => self::$secretKey,
            'response' => $recaptchaResponse
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 5
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            return false; // Erro na comunicação
        }

        $resultData = json_decode($result, true);

        return isset($resultData['success']) && $resultData['success'] === true;
    }
}
