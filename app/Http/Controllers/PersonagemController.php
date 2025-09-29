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
    $orderBy = $request->input('orderBy', 'name'); // 1. Pega o parâmetro de ordenação

    $paginaAtual = $request->input('page', 1);
    $offset = ($paginaAtual - 1) * $limit;

    // 2. Passa o $orderBy para o seu service
    $dados = $this->marvelService->getPersonagens($limit, $termoBusca, $offset, $orderBy);

    $personagensPaginados = new LengthAwarePaginator(
        $dados['personagens'],
        $dados['total'],
        $limit,
        $paginaAtual,
        ['path' => route('personagens.index')]
    );

    // 3. Adiciona TODOS os parâmetros de query atuais aos links da paginação
    $personagensPaginados->appends($request->all());
    
    // 4. Retorna a view, passando o paginador e o orderBy para o seletor
    return view('personagens.index', [
        'personagens' => $personagensPaginados,
        'orderBy' => $orderBy // Adicionado para manter o seletor selecionado
    ]);
}
    /**
     * Exibe os detalhes de um personagem específico.
     */
    public function show(string $id)
{
    // 1. Busca os dados do personagem (como você já fazia)
    $personagem = $this->marvelService->getPersonagemPorId($id);

    if (!$personagem) {
        abort(404);
    }
    
    // 2. NOVA LINHA: Busca os quadrinhos daquele personagem
    // (Lembre-se de adicionar o método 'getComicsByCharacterId' no seu service!)
    $comics = $this->marvelService->getComicsByCharacterId((int)$id, 12);

    // 3. Envia AMBOS, o personagem e seus quadrinhos, para a view
    return view('personagens.show', [
        'personagem' => $personagem,
        'comics' => $comics
    ]);
}
}