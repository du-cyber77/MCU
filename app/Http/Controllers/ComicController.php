<?php

namespace App\Http\Controllers;

use App\Services\MarvelApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ComicController extends Controller
{
    protected $marvelService;

    public function __construct(MarvelApiService $marvelService)
    {
        $this->marvelService = $marvelService;
    }

    public function index(Request $request)
    {
        $limit = 20;
        $allowedOrderBy = ['title', '-title', 'onsaleDate', '-onsaleDate'];

        $tituloBusca = $request->input('busca');
        if (is_string($tituloBusca)) {
            $tituloBusca = trim($tituloBusca);
            $tituloBusca = $tituloBusca !== '' ? mb_substr($tituloBusca, 0, 50) : null;
        } else {
            $tituloBusca = null;
        }

        $orderBy = $request->input('orderBy', 'title');
        if (!in_array($orderBy, $allowedOrderBy, true)) {
            $orderBy = 'title';
        }

        $paginaAtual = (int) $request->input('page', 1);
        if ($paginaAtual < 1) {
            $paginaAtual = 1;
        }
        $offset = ($paginaAtual - 1) * $limit;

        $dados = $this->marvelService->getComics($limit, $tituloBusca, $offset, $orderBy);

        $comicsPaginados = new LengthAwarePaginator(
            $dados['comics'],
            $dados['total'],
            $limit,
            $paginaAtual,
            ['path' => route('comics.index')]
        );

        $comicsPaginados->appends([
            'busca' => $tituloBusca,
            'orderBy' => $orderBy,
            'page' => $paginaAtual,
        ]);

        return view('comics.index', [
            'comics' => $comicsPaginados,
            'orderBy' => $orderBy,
        ]);
    }

    public function show(string $id)
    {
        $comic = $this->marvelService->getComicPorId($id);

        if (!$comic) {
            abort(404);
        }

        return view('comics.show', ['comic' => $comic]);
    }
}
