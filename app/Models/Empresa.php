<?php

namespace App\Models;

use PDO;
use App\Core\Database;

class Empresa
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function criar(string $nome, string $email, string $senha, string $token): bool
    {
        // Verificar se o email já existe
        $stmt = $this->pdo->prepare("SELECT id FROM empresas WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            return false; // E-mail já existe
        }

        // Hash da senha
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir nova empresa
        $stmt = $this->pdo->prepare("INSERT INTO empresas (nome, email, senha, token) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome, $email, $hash, $token]);
    }

    public function confirmar(string $token): bool
    {
        $stmt = $this->pdo->prepare("UPDATE empresas SET confirmado = 1 WHERE token = ?");
        return $stmt->execute([$token]);
    }

    public function verificarLogin(string $email, string $senha): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE email = ?");
        $stmt->execute([$email]);
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($empresa && password_verify($senha, $empresa['senha']) && $empresa['confirmado']) {
            return $empresa;
        }

        return null;
    }

    public function encontrarPorToken(string $token): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE token = :token");
        $stmt->execute(['token' => $token]);
        $resultado = $stmt->fetch();

        return $resultado !== false ? $resultado : null;
    }

    public function emailExiste(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM empresas WHERE email = ?");
        $stmt->execute([$email]);
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $existe !== false;
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE id = ?");
        $stmt->execute([$id]);
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

        return $empresa !== false ? $empresa : null;
    }

    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM empresas WHERE email = ?");
        $stmt->execute([$email]);
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

        return $empresa !== false ? $empresa : null;
    }

    public function salvarTokenRecuperacao(int $id, string $token): bool
    {
        $stmt = $this->pdo->prepare("UPDATE empresas SET token = ? WHERE id = ?");
        return $stmt->execute([$token, $id]);
    }

    public function atualizarSenhaPorToken(string $token, string $novaSenha): bool
    {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("UPDATE empresas SET senha = ?, token = NULL WHERE token = ?");
        return $stmt->execute([$hash, $token]);
    }

}
