<?php

namespace App\Models;

use DateTime;
use PDO;
use App\Core\Database;

class Candidato
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function criar(string $nome, string $email, string $senha, string $token): bool
    {
        // Verificar se o email já existe
        $stmt = $this->pdo->prepare("SELECT id FROM candidatos WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            echo "E-mail já registado!";
            return false;
        }

        // Se não existir, faz o insert
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $expira = (new DateTime())->modify('+30 minutes')->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("INSERT INTO candidatos (nome, email, senha, token, token_expires_at) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nome, $email, $hash, $token, $expira]);
    }

    public function confirmar(string $token): bool
    {
        $stmt = $this->pdo->prepare("
        UPDATE candidatos 
        SET confirmado = 1, token = NULL, token_expires_at = NULL 
        WHERE token = ?
    ");
        return $stmt->execute([$token]);
    }

    public function encontrarPorToken(string $token): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM candidatos 
            WHERE token = :token 
            AND token_expires_at >= NOW()
        ");
        $stmt->execute(['token' => $token]);
        $resultado = $stmt->fetch();

        return $resultado !== false ? $resultado : null;
    }

    public function verificarLogin(string $email, string $senha): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM candidatos WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha']) && $user['confirmado']) {
            return $user;
        }

        return null;
    }

    public function encontrarPorTokenRecuperacao(string $token): ?array
    {
        $stmt = $this->pdo->prepare('
        SELECT * FROM candidatos 
        WHERE token_recuperacao = :token 
        AND token_recuperacao_expires_at >= NOW()
    ');
        $stmt->execute(['token' => $token]);
        $resultado = $stmt->fetch();

        return $resultado !== false ? $resultado : null;
    }

    public function emailExiste(string $email): bool
    {
        $pdo = \App\Core\Database::connect();
        $stmt = $pdo->prepare("SELECT id FROM candidatos WHERE email = ?");
        $stmt->execute([$email]);
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $existe !== false;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM candidatos WHERE id = ?");
        $stmt->execute([$id]);
        $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

        return $utilizador !== false ? $utilizador : null;
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM candidatos WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user !== false ? $user : null;
    }

    public function salvarTokenRecuperacao(int $id, string $token): bool
    {
        $expira = date('Y-m-d H:i:s', strtotime('+60 seconds'));

        $stmt = $this->pdo->prepare("
            UPDATE candidatos
            SET token_recuperacao = ?, token_recuperacao_expires_at = ?
            WHERE id = ?
        ");

        return $stmt->execute([$token, $expira, $id]);
    }

    public function atualizarSenhaPorToken(string $token, string $novaSenha): bool
    {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $verifica = $this->pdo->prepare("
            SELECT id FROM candidatos
            WHERE token_recuperacao = ? AND token_recuperacao_expires_at >= NOW()
        ");
        $verifica->execute([$token]);

        if (!$verifica->fetch()) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE candidatos 
            SET senha = ?, token_recuperacao = NULL, token_recuperacao_expires_at = NULL 
            WHERE token_recuperacao = ?
        ");
        return $stmt->execute([$hash, $token]);
    }

    public function atualizarTokenConfirmacao(string $email, string $novoToken, string $novaExpiracao): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE candidatos 
            SET token = ?, token_expires_at = ? 
            WHERE email = ?
        ");

        return $stmt->execute([$novoToken, $novaExpiracao, $email]);
    }

}
