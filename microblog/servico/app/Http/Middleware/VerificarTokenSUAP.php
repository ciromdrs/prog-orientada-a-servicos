<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class VerificarTokenSUAP
{
    /**
     * Verifica se tem um token SUAP válido na seção.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        # A autorização é feita com o token Bearer do SUAP
        $suap_token = $request->bearerToken();
        # Se a requisição não tem o token, retorna erro
        if (empty($suap_token)) {
            return response()->json([
                'tipo' => 'erro',
                'conteudo' => 'Não autorizado'
            ], 401);
        }

        $dados_SUAP = null;
        # Verifica se os dados usuário dono do token estão no Cache
        if (Cache::has($suap_token)) {
            # Se estão no Cache, reaproveita
            $dados_SUAP = Cache::get($suap_token);
        } else {
            # Se não estão no Cache, pega no SUAP
            $resp = Http::withToken($suap_token)
                ->acceptJson()
                ->get('https://suap.ifrn.edu.br/api/v2/minhas-informacoes/meus-dados/')
                ->getBody()->getContents();
            
            # Decodifica o JSON
            $json = json_decode(
                $resp,
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
            
            # Seleciona apenas os dados que interessam
            $dados_SUAP = [
                'nome' => $json['nome_usual'],
                'matricula' => $json['matricula'],
            ];

            # Salva em Cache
            Cache::set($suap_token, $dados_SUAP);
        }
        $request->attributes->set('usuario', $dados_SUAP);
        # Passou na verificação e o cache está criado
        return $next($request);
    }


    /**
     * Pega os dados do usuário no SUAP.
     * 
     * @param string $suap_token Token JWT gerado pelo SUAP no URI
     * https://suap.ifrn.edu.br/api/v2/autenticacao/token/.
     * 
     * @return array Os dados do usuário no SUAP.
     */
    private function getDadosUsuarioSUAP($suap_token): array {
        $res = json_decode(
            Http::withToken($suap_token)
                ->acceptJson()
                ->get('https://suap.ifrn.edu.br/api/v2/minhas-informacoes/meus-dados/')
                ->getBody()->getContents(),
            associative: true
        );

        $dados = [
            'nome' => $res['nome_usual'],
            'matricula' => $res['matricula']
            # Poderia retornar mais dados aqui
        ];

        return $dados;
    }
}
