<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostra a página inicial da aplicação.
     */
    public function index()
    {
        return view('home'); // Retorna a view 'resources/views/home.blade.php'
    }
}