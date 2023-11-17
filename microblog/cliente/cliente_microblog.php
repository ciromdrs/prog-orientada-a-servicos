<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;

class ClienteMicroblog {
    private GuzzleClient $http_client;


    public function __construct(private string $url_servico) {
        $this->http_client = new GuzzleClient(['base_uri' => $this->url_servico]);
    }


    public function GET_publicacoes(): array {
        $resposta = $this->http_client->request("GET", 'publicacoes');
        $publicacoes = json_decode($resposta->getBody());
        return $publicacoes;
    }

    
    public function POST_publicacoes($p) {
        $resposta = $this->http_client->post(
            'publicacoes',
            ['json' => $p]
        );
        return $resposta;
    }
}