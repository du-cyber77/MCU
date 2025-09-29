<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PersonagemController;
use App\Http\Controllers\ComicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota Principal
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rotas de Personagens
Route::get('/personagens', [PersonagemController::class, 'index'])->name('personagens.index');
Route::get('/personagens/{id}', [PersonagemController::class, 'show'])->name('personagens.show');

// Rotas de Quadrinhos
Route::get('/quadrinhos', [ComicController::class, 'index'])->name('comics.index');
Route::get('/quadrinhos/{id}', [ComicController::class, 'show'])->name('comics.show');