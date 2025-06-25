<?php
require __DIR__ . '/vendor/autoload.php';

use App\Services\RabbitPublisher;

// Conexão PDO com o teu MySQL
$pdo = new PDO('mysql:host=localhost;dbname=exemplo_autocomplete', 'root', '1112');

// Indexar todas as profissões
$stmt = $pdo->query("SELECT id, nome FROM profissoes");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    RabbitPublisher::publish('fila_elasticsearch', [
        'tabela' => 'profissoes',
        'id' => $row['id'],
        'nome' => $row['nome'],
        'nome_normalizado' => strtolower(removerAcentos($row['nome']))
    ]);
}

// Indexar todas as cidades
$stmt = $pdo->query("SELECT id, nome FROM cidades");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    RabbitPublisher::publish('fila_elasticsearch', [
        'tabela' => 'cidades',
        'id' => $row['id'],
        'nome' => $row['nome'],
        'nome_normalizado' => strtolower(removerAcentos($row['nome']))
    ]);
}

echo "✅ Mensagens de todas as profissões e cidades enviadas para RabbitMQ.\n";

// Função para remover acentos
function removerAcentos($texto) {
    $map = [
        '/[áàãâä]/u' => 'a',
        '/[éèêë]/u'  => 'e',
        '/[íìîï]/u'  => 'i',
        '/[óòõôö]/u' => 'o',
        '/[úùûü]/u'  => 'u',
        '/[ç]/u'     => 'c',
    ];
    return preg_replace(array_keys($map), array_values($map), mb_strtolower($texto));
}
