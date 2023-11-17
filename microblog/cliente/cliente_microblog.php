<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;


/**
 * Cliente da API do Microblog.
 */
class ClienteMicroblog {
    private GuzzleClient $guzzle;


    /**
     * @param string url_servico - URL da API.
     */
    public function __construct(private string $url_servico) {
        $this->guzzle = new GuzzleClient([
            'base_uri' => $this->url_servico,
            'http_errors' => false
        ]);
    }


    /**
     * Retorna a lista de publicações.
     */
    public function getPublicacoes(): array {
        $resposta = $this->guzzle->request("GET", 'publicacoes');
        $publicacoes = json_decode($resposta->getBody());
        return $publicacoes;
    }


    /**
     * Cria uma publicação.
     * 
     * @param array p - array contendo os dados da publicação.
     */
    public function criarPublicacao($p) {
        $resposta = $this->guzzle->post(
            'publicacoes',
            ['json' => $p]
        );
        return $resposta;
    }

    
    /**
     * Excui uma publicação.
     * 
     * @param int id - ID da publicação a ser excluída.
     */
    public function exluirPublicacao($id) {
        $resposta = $this->guzzle->delete("publicacoes/$id");
        return $resposta;
    }
}