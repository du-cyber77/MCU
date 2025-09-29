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

    // app/Services/MarvelApiService.php

public function getPersonagens(int $limit = 20, ?string $termoBusca = null, int $offset = 0, string $orderBy = 'name')
{
    // 1. A chave de cache agora inclui o parâmetro de ordenação para ser única
    $cacheKey = "personagens.{$termoBusca}.limit.{$limit}.offset.{$offset}.orderBy.{$orderBy}";

    // 2. Definimos a duração do cache (em segundos). 600 segundos = 10 minutos.
    $duracaoCache = 600; 
    
    // 3. "Envelopamos" toda a lógica com Cache::remember
    return Cache::remember($cacheKey, $duracaoCache, function () use ($limit, $termoBusca, $offset, $orderBy) {
        
        // O código abaixo só será executado se o resultado NÃO estiver no cache
        
        $params = array_merge($this->getAuthParams(), [
            'limit' => $limit,
            'offset' => $offset,
            'orderBy' => $orderBy // 4. Usamos a variável $orderBy que vem da função
        ]);

        if (!empty($termoBusca)) {
            $params['nameStartsWith'] = $termoBusca;
        }

        $response = Http::get($this->baseUrl . 'characters', $params);
        $data = $response->json()['data'] ?? ['results' => [], 'total' => 0];

        // 5. O formato do retorno foi mantido como o seu original
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

public function getSeries(int $limit = 20, int $offset = 0)
{
    $url = $this->baseUrl . 'series';
    $params = $this->getAuthParams();
    $params['limit'] = $limit;
    $params['offset'] = $offset;
    // Você pode adicionar outros filtros como 'orderBy' => 'title'
    // $params['orderBy'] = 'title';

    $cacheKey = 'series_' . md5(implode('', $params));

    return Cache::remember($cacheKey, now()->addHours(24), function () use ($url, $params) {
        $response = Http::get($url, $params);
        return $response->json()['data'];
    });
}

public function findSerieById(int $id)
{
    $url = $this->baseUrl . 'series/' . $id;
    $params = $this->getAuthParams();

    $cacheKey = 'serie_' . $id;

    return Cache::remember($cacheKey, now()->addHours(24), function () use ($url, $params) {
        $response = Http::get($url, $params);
        return $response->json()['data']['results'][0];
    });
}


public function getEvents(string $orderBy = 'startDate', int $limit = 100) // Aumentei o limite para 100
{
    $cacheKey = "events.orderBy.{$orderBy}.limit.{$limit}.v2"; // Mudei a chave do cache
    $duracaoCache = 1440; 

    return Cache::remember($cacheKey, $duracaoCache, function () use ($orderBy, $limit) {
        $params = array_merge($this->getAuthParams(), [
            'limit' => $limit,
            'orderBy' => $orderBy
        ]);

        $response = Http::get($this->baseUrl . 'events', $params);
        $data = $response->json()['data'] ?? ['results' => [], 'total' => 0];

        // CORREÇÃO: Agora filtramos APENAS por imagem válida.
        // Eventos sem descrição serão permitidos.
        $filteredResults = array_filter($data['results'], function ($event) {
            return !str_contains($event['thumbnail']['path'], 'image_not_available');
        });

        return [
            'events' => array_values($filteredResults),
            'total' => count($filteredResults)
        ];
    });
}

public function getComicsByCharacterId(int $characterId, int $limit = 10)
{
    $cacheKey = "character.{$characterId}.comics.limit.{$limit}";
    $duracaoCache = 1440; // Cache de 24 horas

    return Cache::remember($cacheKey, $duracaoCache, function () use ($characterId, $limit) {
        $params = array_merge($this->getAuthParams(), [
            'limit' => $limit,
            'orderBy' => '-onsaleDate' // Ordena pelos mais recentes primeiro
        ]);

        $response = Http::get($this->baseUrl . "characters/{$characterId}/comics", $params);
        $data = $response->json()['data'] ?? ['results' => [], 'total' => 0];

        // Filtra quadrinhos que não têm imagem
        $filteredResults = array_filter($data['results'], function ($comic) {
            return !str_contains($comic['thumbnail']['path'], 'image_not_available');
        });

        return array_values($filteredResults); // Retorna apenas a lista de quadrinhos
    });
}





}



