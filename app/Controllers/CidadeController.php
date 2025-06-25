<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\RabbitPublisher;

class CidadeController
{
    public function criar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';

            if (empty($nome)) {
                echo "Nome da cidade é obrigatório.";
                return;
            }

            $nome_normalizado = strtolower(self::removeAcentos($nome));

            // Gravar no MySQL usando conexão centralizada
            $pdo = Database::connect();
            $stmt = $pdo->prepare("INSERT INTO cidades (nome, nome_normalizado) VALUES (?, ?)");
            $stmt->execute([$nome, $nome_normalizado]);
            $novoId = $pdo->lastInsertId();

            // Enviar evento para RabbitMQ
            RabbitPublisher::publish('fila_elasticsearch', [
                'tabela' => 'cidades',
                'id' => $novoId,
                'nome' => $nome,
                'nome_normalizado' => $nome_normalizado
            ]);

            echo "✅ Cidade cadastrada e evento enviado para o RabbitMQ.";
        } else {
            echo '<form method="POST">
                    Nome da Cidade: <input type="text" name="nome">
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
