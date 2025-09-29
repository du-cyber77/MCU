<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MarvelApiService;

class SerieController extends Controller
{
    protected $marvelApiService;

    public function __construct(MarvelApiService $marvelApiService)
    {
        $this->marvelApiService = $marvelApiService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $offset = ($page - 1) * 20;

        $seriesData = $this->marvelApiService->getSeries(20, $offset);
        $series = $seriesData['results'];
        $total = $seriesData['total'];

        // Lógica de paginação manual
        $totalPages = ceil($total / 20);

        return view('series.index', compact('series', 'page', 'totalPages'));
    }

    public function show($id)
    {
        $serie = $this->marvelApiService->findSerieById($id);

        return view('series.show', compact('serie'));
    }
}