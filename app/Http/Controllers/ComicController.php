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
        $tituloBusca = $request->input('busca');
        $paginaAtual = $request->input('page', 1);
        $offset = ($paginaAtual - 1) * $limit;

        $dados = $this->marvelService->getComics($limit, $tituloBusca, $offset);

        $comicsPaginados = new LengthAwarePaginator(
            $dados['comics'],
            $dados['total'],
            $limit,
            $paginaAtual,
            ['path' => route('comics.index')]
        );

        if ($tituloBusca) {
            $comicsPaginados->appends(['busca' => $tituloBusca]);
        }

        return view('comics.index', ['comics' => $comicsPaginados]);
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