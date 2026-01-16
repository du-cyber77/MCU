<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache; // <-- 1. IMPORTE A FACADE DO CACHE
use Illuminate\Support\Facades\Log;

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

    private function normalizeSearchTerm(?string $term): ?string
    {
        if ($term === null) {
            return null;
        }

        $normalized = trim(mb_strtolower($term));

        return $normalized !== '' ? $normalized : null;
    }

    private function request(string $endpoint, array $params = []): array
    {
        $url = $this->baseUrl . ltrim($endpoint, '/');
        $mergedParams = array_merge($this->getAuthParams(), $params);

        $response = Http::timeout(10)
            ->retry(3, 200)
            ->get($url, $mergedParams);

        if (!$response->successful()) {
            Log::warning('Marvel API request failed.', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'params' => $params,
            ]);

            return ['results' => [], 'total' => 0];
        }

        $data = $response->json()['data'] ?? null;

        if (!is_array($data)) {
            Log::warning('Marvel API response missing data payload.', [
                'endpoint' => $endpoint,
                'params' => $params,
            ]);

            return ['results' => [], 'total' => 0];
        }

        return $data;
    }

    // app/Services/MarvelApiService.php

    public function getPersonagens(int $limit = 20, ?string $termoBusca = null, int $offset = 0, string $orderBy = 'name')
    {
        $normalizedTerm = $this->normalizeSearchTerm($termoBusca);
        $termHash = $normalizedTerm ? md5($normalizedTerm) : 'none';

        // 1. A chave de cache agora inclui o parâmetro de ordenação para ser única
        $cacheKey = "personagens.term.{$termHash}.limit.{$limit}.offset.{$offset}.orderBy.{$orderBy}";

        // 2. Definimos a duração do cache (em segundos). 600 segundos = 10 minutos.
        $duracaoCache = 600;

        // 3. "Envelopamos" toda a lógica com Cache::remember
        return Cache::remember($cacheKey, $duracaoCache, function () use ($limit, $normalizedTerm, $offset, $orderBy) {
            // O código abaixo só será executado se o resultado NÃO estiver no cache
            $params = [
                'limit' => $limit,
                'offset' => $offset,
                'orderBy' => $orderBy // 4. Usamos a variável $orderBy que vem da função
            ];

            if (!empty($normalizedTerm)) {
                $params['nameStartsWith'] = $normalizedTerm;
            }

            $data = $this->request('characters', $params);

            // 5. O formato do retorno foi mantido como o seu original
            return [
                'personagens' => $data['results'] ?? [],
                'total' => $data['total'] ?? 0
            ];
        });
    }
    public function getPersonagemPorId(int $id)
    {
        // BÔNUS: Você pode aplicar a mesma lógica para a página de detalhes!
        $cacheKey = "personagem.{$id}";

        return Cache::remember($cacheKey, 3600, function () use ($id) { // Cache de 1 hora
            $data = $this->request('characters/' . $id);
            return $data['results'][0] ?? null;
        });
    }

    public function getComics(int $limit = 20, ?string $tituloBusca = null, int $offset = 0, string $orderBy = 'title')
    {
        $normalizedTitle = $this->normalizeSearchTerm($tituloBusca);
        $termHash = $normalizedTitle ? md5($normalizedTitle) : 'none';

        $cacheKey = "comics.term.{$termHash}.limit.{$limit}.offset.{$offset}.orderBy.{$orderBy}";
        $duracaoCache = 600; // 10 minutos

        return Cache::remember($cacheKey, $duracaoCache, function () use ($limit, $normalizedTitle, $offset, $orderBy) {
            $params = [
                'limit' => $limit,
                'orderBy' => $orderBy, // Ordenação dinâmica
                'offset' => $offset
            ];

            if (!empty($normalizedTitle)) {
                $params['titleStartsWith'] = $normalizedTitle; // Filtro por título
            }

            $data = $this->request('comics', $params); // Endpoint de comics

            return [
                'comics' => $data['results'] ?? [],
                'total' => $data['total'] ?? 0
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
            $data = $this->request('comics/' . $id);
            return $data['results'][0] ?? null;
        });
    }

    public function getSeries(int $limit = 20, int $offset = 0)
    {
        $params = [
            'limit' => $limit,
            'offset' => $offset
        ];
        // Você pode adicionar outros filtros como 'orderBy' => 'title'
        // $params['orderBy'] = 'title';

        $cacheKey = 'series_' . md5(implode('', $params));

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($params) {
            return $this->request('series', $params);
        });
    }

    public function findSerieById(int $id)
    {
        $cacheKey = 'serie_' . $id;

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($id) {
            $data = $this->request('series/' . $id);
            return $data['results'][0] ?? null;
        });
    }


    public function getEvents(string $orderBy = 'startDate', int $limit = 100) // Aumentei o limite para 100
    {
        $cacheKey = "events.orderBy.{$orderBy}.limit.{$limit}.v2"; // Mudei a chave do cache
        $duracaoCache = 1440;

        return Cache::remember($cacheKey, $duracaoCache, function () use ($orderBy, $limit) {
            $params = [
                'limit' => $limit,
                'orderBy' => $orderBy
            ];

            $data = $this->request('events', $params);

            // CORREÇÃO: Agora filtramos APENAS por imagem válida.
            // Eventos sem descrição serão permitidos.
            $filteredResults = array_filter($data['results'] ?? [], function ($event) {
                $thumbnailPath = $event['thumbnail']['path'] ?? '';
                return $thumbnailPath !== '' && !str_contains($thumbnailPath, 'image_not_available');
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
            $params = [
                'limit' => $limit,
                'orderBy' => '-onsaleDate' // Ordena pelos mais recentes primeiro
            ];

            $data = $this->request("characters/{$characterId}/comics", $params);

            // Filtra quadrinhos que não têm imagem
            $filteredResults = array_filter($data['results'] ?? [], function ($comic) {
                $thumbnailPath = $comic['thumbnail']['path'] ?? '';
                return $thumbnailPath !== '' && !str_contains($thumbnailPath, 'image_not_available');
            });

            return array_values($filteredResults); // Retorna apenas a lista de quadrinhos
        });
    }





}
