<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;

class ClienteMicroblog {
    public function __construct(string $url_servico) {
        $this->url_api = $url_servico;
        $this->http_client = new GuzzleClient(['base_uri' => $this->url_api]);
    }


    public function GET_publicacoes(): array {
        $resposta = $this->http_client->request("GET", 'publicacoes');
        $publicacoes = json_decode($resposta->getBody());
        return $publicacoes;
    }
}