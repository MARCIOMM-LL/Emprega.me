<?php

namespace App\Models;

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
        $stmt = $this->pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            echo "E-mail já registado!";
            return false;
        }

        // Se não existir, faz o insert
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO utilizadores (nome, email, senha, token) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome, $email, $hash, $token]);
    }

    public function confirmar(string $token): bool
    {
        $stmt = $this->pdo->prepare("UPDATE utilizadores SET confirmado = 1 WHERE token = ?");
        return $stmt->execute([$token]);
    }

    public function verificarLogin(string $email, string $senha): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha']) && $user['confirmado']) {
            return $user;
        }

        return null;
    }

    public function encontrarPorToken(string $token): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilizadores WHERE token = :token');
        $stmt->execute(['token' => $token]);
        $resultado = $stmt->fetch();

        return $resultado !== false ? $resultado : null;
    }

    public function emailExiste(string $email): bool
    {
        $pdo = \App\Core\Database::connect();
        $stmt = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $existe !== false;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilizadores WHERE id = ?");
        $stmt->execute([$id]);
        $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

        return $utilizador !== false ? $utilizador : null;
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user !== false ? $user : null;
    }

    public function salvarTokenRecuperacao(int $id, string $token): bool
    {
        $stmt = $this->pdo->prepare("UPDATE utilizadores SET token = ? WHERE id = ?");
        return $stmt->execute([$token, $id]);
    }

    public function atualizarSenhaPorToken(string $token, string $novaSenha): bool
    {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE utilizadores SET senha = ?, token = NULL WHERE token = ?");
        return $stmt->execute([$hash, $token]);
    }

}
