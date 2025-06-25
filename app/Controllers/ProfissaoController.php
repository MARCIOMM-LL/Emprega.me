<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\RabbitPublisher;

class ProfissaoController
{
    public function criar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';

            if (empty($nome)) {
                echo "Nome da profissão é obrigatório.";
                return;
            }

            $nome_normalizado = strtolower(self::removeAcentos($nome));

            // Gravar no MySQL usando a conexão centralizada
            $pdo = Database::connect();
            $stmt = $pdo->prepare("INSERT INTO profissoes (nome, nome_normalizado) VALUES (?, ?)");
            $stmt->execute([$nome, $nome_normalizado]);
            $novoId = $pdo->lastInsertId();

            // Enviar evento para o RabbitMQ
            RabbitPublisher::publish('fila_elasticsearch', [
                'tabela' => 'profissoes',
                'id' => $novoId,
                'nome' => $nome,
                'nome_normalizado' => $nome_normalizado
            ]);

            echo "✅ Profissão cadastrada e evento enviado para o RabbitMQ.";
        } else {
            echo '<form method="POST">
                    Nome da Profissão: <input type="text" name="nome">
                    <button type="submit">Salvar</button>
                  </form>';
        }
    }

    // Função auxiliar agora como método privado da classe
    private static function removeAcentos(string $texto): string
    {
        return preg_replace(
            ['/[áàãâä]/u', '/[éèêë]/u', '/[íìîï]/u', '/[óòõôö]/u', '/[úùûü]/u', '/[ç]/u'],
            ['a', 'e', 'i', 'o', 'u', 'c'],
            mb_strtolower($texto)
        );
    }
}
