<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MarvelApiService;

class EventController extends Controller
{
    protected $marvelService;

    public function __construct(MarvelApiService $marvelService)
    {
        $this->marvelService = $marvelService;
    }

 public function timeline()
{
    // MUDANÇA PRINCIPAL: De 'startDate' para '-startDate'
    $dados = $this->marvelService->getEvents('-startDate', 100); 
    $eventos = $dados['events'];

    // Para a linha do tempo fazer sentido, vamos inverter a ordem para o frontend
    // Assim, o evento mais "antigo" (entre os mais recentes) aparece primeiro.
    $eventos = array_reverse($eventos);

    return view('events.timeline', ['events' => $eventos]);
}
}