<?php

namespace App\Helpers;

class FlashHelper
{
    public static function set(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (trim($message) === '') {
            return;
        }

        $_SESSION['flash_type'] = $type;
        $_SESSION['flash_message'] = $message;
    }

    public static function get(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['flash_type']) && !empty($_SESSION['flash_message'])) {
            $flash = [
                'type' => $_SESSION['flash_type'],
                'message' => $_SESSION['flash_message']
            ];
            unset($_SESSION['flash_type'], $_SESSION['flash_message']);
            return $flash;
        }

        // Se não houver, devolve null
        unset($_SESSION['flash_type'], $_SESSION['flash_message']);
        return null;
    }

    public static function has(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return !empty($_SESSION['flash_type']) && !empty($_SESSION['flash_message']);
    }
}
