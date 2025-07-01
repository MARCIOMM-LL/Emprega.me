<?php
namespace App\Helpers;

use App\Models\Candidato;
use App\Models\Empresa;

class AuthHelper
{
    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica se existe um candidato logado (candidato ou empresa).
     * Se o registo foi apagado da BD, faz logout automático.
     *
     * @param string $tipo 'candidato' ou 'empresa'
     * @return array|null
     */
    public static function userOrLogout(string $tipo): ?array
    {
        self::startSession();

        if ($tipo === 'candidato') {
            if (!isset($_SESSION['candidato'])) {
                return null;
            }

            $model = new Candidato();
            $candidato = $model->buscarPorId($_SESSION['candidato']['id']);

            if (!$candidato) {
                session_destroy();
                header('Location: /dashboard');
                exit;
            }

            return $candidato;
        }

        if ($tipo === 'empresa') {
            if (!isset($_SESSION['empresa'])) {
                return null;
            }

            $model = new Empresa();
            $empresa = $model->buscarPorId($_SESSION['empresa']['id']);

            if (!$empresa) {
                session_destroy();
                header('Location: /dashboard');
                exit;
            }

            return $empresa;
        }

        // Tipo inválido
        return null;
    }

    public static function requireGuest()
    {
        self::startSession();
        if (isset($_SESSION['candidato'])) {
            header('Location: /home');
            exit;
        }
    }

    public static function requireLogin()
    {
        self::startSession();
        if (!isset($_SESSION['candidato'])) {
            header('Location: /login-candidato');
            exit;
        }
    }
}
