<?php
namespace App\Helpers;

class CsrfHelper
{
    /**
     * Gera e retorna um token CSRF, guardando-o na sessão se ainda não existir.
     */
    public static function gerarToken(): string
    {
        SessionHelper::start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Valida o token CSRF recebido comparando com o da sessão.
     * Após validação, o token é invalidado (uso único).
     */
    public static function validarToken(string $token): bool
    {
        SessionHelper::start();

        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            unset($_SESSION['csrf_token']); // Torna o token de uso único
            return true;
        }

        return false;
    }
}
