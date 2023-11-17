<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class ClienteMicroblog {
    private GuzzleClient $guzzle;


    public function __construct(private string $url_servico) {
        $this->guzzle = new GuzzleClient([
            'base_uri' => $this->url_servico,
            'http_errors' => false
        ]);
    }


    public function getPublicacoes(): array {
        $resposta = $this->guzzle->request("GET", 'publicacoes');
        $publicacoes = json_decode($resposta->getBody());
        return $publicacoes;
    }


    public function criarPublicacao($p) {
        $resposta = $this->guzzle->post(
            'publicacoes',
            ['json' => $p]
        );
        return $resposta;
    }

    public function exluirPublicacao($id) {
        $resposta = $this->guzzle->delete("publicacoes/$id");
         return $resposta;
    }
}