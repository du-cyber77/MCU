<?php

namespace App\Http\Controllers;

use App\Services\MarvelApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PersonagemController extends Controller
{
    protected $marvelService;

    public function __construct(MarvelApiService $marvelService)
    {
        $this->marvelService = $marvelService;
    }

    /**
     * Exibe uma lista de personagens.
     * ESTE MÉTODO PRECISA EXISTIR!
     */
    public function index(Request $request)
{
    $limit = 24;
    $termoBusca = $request->input('busca');
    
    // O Paginator do Laravel sabe qual é a página atual pela URL (?page=2)
    $paginaAtual = $request->input('page', 1);

    // Calculamos o offset para enviar à API
    $offset = ($paginaAtual - 1) * $limit;

    // Chamamos nosso service, que agora retorna um array
    $dados = $this->marvelService->getPersonagens($limit, $termoBusca, $offset);

    // Criamos manualmente um paginador do Laravel.
    // Ele precisa dos itens da página atual, do total de itens, do limite por página,
    // da página atual, e de uma configuração para os links.
    $personagensPaginados = new LengthAwarePaginator(
        $dados['personagens'],
        $dados['total'],
        $limit,
        $paginaAtual,
        ['path' => route('personagens.index')] // Garante que os links de paginação funcionem
    );

    // Se houver uma busca, adicionamos o termo aos links de paginação
    if ($termoBusca) {
        $personagensPaginados->appends(['busca' => $termoBusca]);
    }
    
    // Retorna a view, passando o objeto paginador
    return view('personagens.index', ['personagens' => $personagensPaginados]);
}
    /**
     * Exibe os detalhes de um personagem específico.
     */
    public function show(string $id)
    {
        $personagem = $this->marvelService->getPersonagemPorId($id);

        if (!$personagem) {
            abort(404);
        }
        
        return view('personagens.show', ['personagem' => $personagem]);
    }
}