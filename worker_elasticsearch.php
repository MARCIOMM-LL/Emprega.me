<?php
//require __DIR__ . '/vendor/autoload.php';
//
//use Elastic\Elasticsearch\ClientBuilder;
//use PhpAmqpLib\Connection\AMQPStreamConnection;
//
//$client = ClientBuilder::create()
//    ->setHosts(['localhost:9200'])
//    ->build();
//
//$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
//$channel = $connection->channel();
//
//$channel->queue_declare('fila_elasticsearch', false, true, false, false);
//
//echo "🟢 Aguardando eventos da fila RabbitMQ...\n";
//
//$callback = function ($msg) use ($client) {
//    $dados = json_decode($msg->body, true);
//
//    if (!empty($dados['tabela']) && !empty($dados['id'])) {
//        try {
//            $client->index([
//                'index' => $dados['tabela'],
//                'id' => $dados['id'],
//                'body' => [
//                    'nome' => $dados['nome'],
//                    'nome_normalizado' => $dados['nome_normalizado']
//                ]
//            ]);
//            echo "✅ Indexado no Elasticsearch: {$dados['tabela']} - {$dados['nome']}\n";
//        } catch (Exception $e) {
//            echo "❌ Erro ao indexar: " . $e->getMessage() . "\n";
//        }
//    } else {
//        echo "❌ Mensagem inválida: " . $msg->body . "\n";
//    }
//};
//
//$channel->basic_consume('fila_elasticsearch', '', false, true, false, false, $callback);
//
//while ($channel->is_consuming()) {
//    $channel->wait();
//}

require __DIR__ . '/vendor/autoload.php';

use Elastic\Elasticsearch\ClientBuilder;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$client = ClientBuilder::create()
    ->setHosts(['localhost:9200'])
    ->build();

try {
    $connection = new AMQPStreamConnection(
        'localhost',
        5672,
        'guest',
        'guest',
        '/',
        false,
        'AMQPLAIN',
        null,
        'en_US',
        120.0, // connection_timeout
        120.0, // read_write_timeout (>= 2x heartbeat)
        null,
        false,
        60 // heartbeat
    );

    $channel = $connection->channel();
    $channel->queue_declare('fila_elasticsearch', false, true, false, false);

    echo "🟢 Worker iniciado e aguardando mensagens da fila RabbitMQ...\n";

    $callback = function ($msg) use ($client) {
        $dados = json_decode($msg->body, true);

        if (!empty($dados['tabela']) && !empty($dados['id'])) {
            try {
                $client->index([
                    'index' => $dados['tabela'],
                    'id' => $dados['id'],
                    'body' => [
                        'nome' => $dados['nome'],
                        'nome_normalizado' => $dados['nome_normalizado']
                    ]
                ]);
                echo "Indexado: {$dados['tabela']} - {$dados['nome']}\n";
            } catch (Exception $e) {
                echo "Erro ao indexar no Elasticsearch: " . $e->getMessage() . "\n";
            }
        } else {
            echo "Mensagem inválida: " . $msg->body . "\n";
        }
    };

    $channel->basic_consume('fila_elasticsearch', '', false, true, false, false, $callback);

    while (true) {
        try {
            $channel->wait();
        } catch (\PhpAmqpLib\Exception\AMQPTimeoutException $e) {
            echo "Timeout de espera da fila: " . $e->getMessage() . "\n";
        } catch (\PhpAmqpLib\Exception\AMQPConnectionClosedException $e) {
            echo "Conexão com RabbitMQ foi fechada: " . $e->getMessage() . "\n";
            break;
        } catch (\Exception $e) {
            echo "Erro inesperado no Worker: " . $e->getMessage() . "\n";
            break;
        }
    }

    $channel->close();
    $connection->close();

} catch (Exception $e) {
    echo "Não foi possível conectar ao RabbitMQ: " . $e->getMessage() . "\n";
}
