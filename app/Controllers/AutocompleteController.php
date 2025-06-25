<?php

namespace App\Controllers;

use Elastic\Elasticsearch\ClientBuilder;

class AutocompleteController
{
    public function pesquisar(): void
    {
        header('Content-Type: application/json');

        $tabela = $_GET['tabela_database'] ?? '';
        $q = $_GET['q'] ?? '';

        $validos = ['profissoes', 'cidades'];

        if (!in_array($tabela, $validos) || empty($q)) {
            echo json_encode([]);
            return;
        }

        $qNormalizado = $this->removerAcentos($q);

        $client = ClientBuilder::create()
            ->setHosts(['localhost:9200'])
            ->build();

        $params = [
            'index' => $tabela,
            'body' => [
                'size' => 10,
                'query' => [
                    'wildcard' => [
                        'nome_normalizado' => [
                            'value' => '*' . strtolower($qNormalizado) . '*',
                            'case_insensitive' => true
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $client->search($params);
            $resultados = [];

            foreach ($response->asArray()['hits']['hits'] as $hit) {
                $resultados[] = ['nome' => $hit['_source']['nome']];
            }

            echo json_encode($resultados, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    private function removerAcentos(string $texto): string
    {
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
}
