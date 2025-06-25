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
     * Verifica se existe um utilizador logado (candidato ou empresa).
     * Se o registo foi apagado da BD, faz logout automático.
     *
     * @param string $tipo 'candidato' ou 'empresa'
     * @return array|null
     */
    public static function userOrLogout(string $tipo): ?array
    {
        self::startSession();

        if ($tipo === 'candidato') {
            if (!isset($_SESSION['utilizador'])) {
                return null;
            }

            $model = new Candidato();
            $utilizador = $model->buscarPorId($_SESSION['utilizador']['id']);

            if (!$utilizador) {
                session_destroy();
                header('Location: /dashboard');
                exit;
            }

            return $utilizador;
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
        session_start();
        if (isset($_SESSION['utilizador'])) {
            header('Location: /home');
            exit;
        }
    }

    public static function requireLogin()
    {
        session_start();
        if (!isset($_SESSION['utilizador'])) {
            header('Location: /login-candidato');
            exit;
        }
    }
}
