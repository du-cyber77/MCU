<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache; // <-- 1. IMPORTE A FACADE DO CACHE

class MarvelApiService
{
    private $publicKey;
    private $privateKey;
    private $baseUrl = 'https://gateway.marvel.com/v1/public/';

    public function __construct()
    {
        $this->publicKey = config('services.marvel.public_key');
        $this->privateKey = config('services.marvel.private_key');
    }

    private function getAuthParams()
{
    $timestamp = now()->timestamp;
    $hash = md5($timestamp . $this->privateKey . $this->publicKey);

    // ✅ GARANTA QUE ESTA LINHA ESTEJA CORRETA E EXISTENTE
    return [
        'ts' => $timestamp,
        'apikey' => $this->publicKey,
        'hash' => $hash,
    ];
}

    public function getPersonagens(int $limit = 20, ?string $termoBusca = null, int $offset = 0)
    {
        // 2. Criamos uma chave única para o cache baseada nos parâmetros da busca
        $cacheKey = "personagens.{$termoBusca}.limit.{$limit}.offset.{$offset}";

        // 3. Definimos a duração do cache (em segundos). 600 segundos = 10 minutos.
        $duracaoCache = 600; 
        
        // 4. "Envelopamos" toda a lógica com Cache::remember
        return Cache::remember($cacheKey, $duracaoCache, function () use ($limit, $termoBusca, $offset) {
            
            // O código abaixo só será executado se o resultado NÃO estiver no cache
            
            $params = array_merge($this->getAuthParams(), [
                'limit' => $limit,
                'orderBy' => 'name',
                'offset' => $offset
            ]);

            if (!empty($termoBusca)) {
                $params['nameStartsWith'] = $termoBusca;
            }

            $response = Http::get($this->baseUrl . 'characters', $params);
            $data = $response->json()['data'] ?? ['results' => [], 'total' => 0];

            return [
                'personagens' => $data['results'],
                'total' => $data['total']
            ];
        });
    }

    public function getPersonagemPorId(int $id)
    {
        // BÔNUS: Você pode aplicar a mesma lógica para a página de detalhes!
        $cacheKey = "personagem.{$id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($id) { // Cache de 1 hora
             $response = Http::get($this->baseUrl . 'characters/' . $id, $this->getAuthParams());
             return $response->json()['data']['results'][0] ?? null;
        });
    }

    public function getComics(int $limit = 20, ?string $tituloBusca = null, int $offset = 0)
{
    $cacheKey = "comics.{$tituloBusca}.limit.{$limit}.offset.{$offset}";
    $duracaoCache = 600; // 10 minutos

    return Cache::remember($cacheKey, $duracaoCache, function () use ($limit, $tituloBusca, $offset) {
        $params = array_merge($this->getAuthParams(), [
            'limit' => $limit,
            'orderBy' => 'title', // Ordenar por título
            'offset' => $offset
        ]);

        if (!empty($tituloBusca)) {
            $params['titleStartsWith'] = $tituloBusca; // Filtro por título
        }

        $response = Http::get($this->baseUrl . 'comics', $params); // Endpoint de comics
        $data = $response->json()['data'] ?? ['results' => [], 'total' => 0];

        return [
            'comics' => $data['results'],
            'total' => $data['total']
        ];
    });
}

/**
 * Busca um quadrinho específico pelo seu ID.
 */
public function getComicPorId(int $id)
{
    $cacheKey = "comic.{$id}";
    $duracaoCache = 3600; // 1 hora

    return Cache::remember($cacheKey, $duracaoCache, function () use ($id) {
        $response = Http::get($this->baseUrl . 'comics/' . $id, $this->getAuthParams());
        return $response->json()['data']['results'][0] ?? null;
    });
}
}